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
     * @param Request $request Contiene: email, farm_id, role
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,operario'
        ]);

        try {
            // Obtener el ID de la granja de la URL
            $farm_id = $request->query('farm_id');
            if (!$farm_id) {
                \Log::error('Farm ID no encontrado en la URL');
                return redirect()->back()->with('error', 'Error: No se pudo identificar la granja actual');
            }

            // Verificar que la granja pertenece al usuario actual
            $farm = Farm::findOrFail($farm_id);
            if ($farm->users_role_id !== auth()->user()->userRole->id) {
                return redirect()->back()->with('error', 'No tienes permiso para invitar usuarios a esta granja');
            }

            // Crear la invitación
            $invitation = Invitation::create([
                'email' => $request->email,
                'role' => $request->role,
                'farm_id' => $farm_id,
                'token' => Invitation::generateToken(),
                'expires_at' => Carbon::now()->addHours(24)
            ]);

            // Enviar el correo con manejo de errores detallado
            try {
                \Log::info('Intentando enviar correo a: ' . $request->email);
                Mail::to($request->email)->send(new InvitationMail($invitation));
                \Log::info('Invitación enviada exitosamente');
                
                return redirect()->back()->with('success', 'Invitación enviada al correo ' . $request->email);
            } catch (\Exception $e) {
                \Log::error('Error detallado al enviar el correo: ' . $e->getMessage());
                \Log::error('Trace: ' . $e->getTraceAsString());
                return redirect()->back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            \Log::error('Error en el proceso de invitación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar la invitación: ' . $e->getMessage());
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
