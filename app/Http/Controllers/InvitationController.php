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
     * @param Request $request Contiene: email, role
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,operario'
        ]);

        try {
            // Obtener el ID de la granja de la sesión
            $farm_id = session('current_farm_id');
            if (!$farm_id) {
                \Log::error('Farm ID no encontrado en la sesión');
                return redirect()->back()->with('error', 'Error: No se pudo identificar la granja actual');
            }

            // Verificar que la granja existe
            $farm = Farm::findOrFail($farm_id);

            // Verificar si el usuario ya está invitado
            $existingInvitation = Invitation::where('email', $request->email)
                ->where('farm_id', $farm_id)
                ->where('accepted', false)
                ->where('expires_at', '>', now())
                ->first();

            if ($existingInvitation) {
                return redirect()->back()->with('error', 'Ya existe una invitación pendiente para este usuario');
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

            // Enviar el correo
            Mail::to($request->email)->send(new InvitationMail($invitation));

            return redirect()->back()->with('success', 'Invitación enviada exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al enviar invitación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al enviar la invitación: ' . $e->getMessage());
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
