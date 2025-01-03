<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invitation;
use App\Models\Farm;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;
use Carbon\Carbon;

/**
 * Controlador InvitationController
 * 
 * Este controlador maneja todas las operaciones relacionadas con las invitaciones a granjas:
 * - Envío de invitaciones por correo
 * - Aceptación de invitaciones
 * - Verificación de tokens
 * - Asignación de roles en granjas
 */
class InvitationController extends Controller
{
    /**
     * Envía una invitación por correo electrónico
     * 
     * @param Request $request Contiene: email, role, farm_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        // Log de los datos recibidos
        \Log::info('Datos de invitación recibidos:', $request->all());

        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,operario'
        ]);

        try {
            // Verificar si el usuario existe en la base de datos
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                throw new \Exception('El correo electrónico no está registrado en el sistema. El usuario debe registrarse primero.');
            }

            // Obtener el ID de la granja de la sesión o del request
            $farm_id = $request->farm_id ?? session('current_farm_id');
            if (!$farm_id) {
                throw new \Exception('No se pudo identificar la granja actual');
            }

            // Verificar que la granja existe
            $farm = Farm::findOrFail($farm_id);
            \Log::info('Granja encontrada:', ['farm' => $farm->toArray()]);

            // Verificar si el usuario ya está en la granja
            if ($user->farms()->where('farm_id', $farm_id)->exists()) {
                throw new \Exception('Este usuario ya es miembro de la granja');
            }

            // Verificar si el usuario ya está invitado
            $existingInvitation = Invitation::where('email', $request->email)
                ->where('farm_id', $farm_id)
                ->where('accepted', false)
                ->where('expires_at', '>', now())
                ->first();

            if ($existingInvitation) {
                throw new \Exception('Ya existe una invitación pendiente para este usuario');
            }

            // Crear la invitación
            $invitation = Invitation::create([
                'email' => $request->email,
                'farm_id' => $farm_id,
                'role' => $request->role,
                'token' => \Str::random(32),
                'expires_at' => now()->addDays(7),
                'accepted' => false
            ]);

            \Log::info('Invitación creada:', ['invitation' => $invitation->toArray()]);

            // Enviar el correo
            Mail::to($request->email)->send(new InvitationMail($invitation));
            \Log::info('Correo enviado exitosamente');
            
            // Responder según el tipo de solicitud
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invitación enviada exitosamente'
                ]);
            }
            
            return redirect()->route('dashboard.users', ['farm_id' => $farm_id])
                ->with('success', 'Invitación enviada exitosamente');

        } catch (\Exception $e) {
            \Log::error('Error al enviar invitación: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('dashboard.users', ['farm_id' => $farm_id])
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Acepta una invitación y asigna el rol al usuario
     * 
     * @param string $token Token único de la invitación
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept($token)
    {
        \Log::info('Aceptando invitación con token: ' . $token);
        
        $invitation = Invitation::where('token', $token)
                              ->where('accepted', false)
                              ->first();

        if (!$invitation) {
            \Log::warning('Invitación no encontrada o ya utilizada');
            return redirect()->route('login')->with('error', 'Invitación inválida o ya utilizada');
        }

        if ($invitation->isExpired()) {
            \Log::warning('Invitación expirada');
            return redirect()->route('login')->with('error', 'La invitación ha expirado');
        }

        $user = User::where('email', $invitation->email)->first();
        
        if ($user) {
            \Log::info('Usuario encontrado: ' . $user->email);
            
            // Actualizar el rol del usuario existente si es necesario
            if ($invitation->role === 'admin' && $user->role !== 'super_admin') {
                $user->update(['role' => $invitation->role]);
                \Log::info('Rol de usuario actualizado a: ' . $invitation->role);
            }
            
            try {
                // Crear relación entre usuario y granja usando la tabla pivote farm_user
                $user->farms()->attach($invitation->farm_id, [
                    'role' => $invitation->role
                ]);
                \Log::info('Relación creada entre usuario y granja. User ID: ' . $user->id . ', Farm ID: ' . $invitation->farm_id . ', Role: ' . $invitation->role);
            } catch (\Exception $e) {
                \Log::error('Error al crear relación: ' . $e->getMessage());
                return redirect()->route('login')->with('error', 'Error al procesar la invitación: ' . $e->getMessage());
            }
        } else {
            \Log::warning('Usuario no encontrado para el email: ' . $invitation->email);
        }

        // Marcar la invitación como aceptada
        $invitation->update(['accepted' => true]);
        \Log::info('Invitación marcada como aceptada');

        return redirect()->route('login')->with('success', 'Invitación aceptada correctamente. Por favor inicia sesión.');
    }
}
