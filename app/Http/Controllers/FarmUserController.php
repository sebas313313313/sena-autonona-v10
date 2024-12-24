<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;
use Carbon\Carbon;

class FarmUserController extends Controller
{
    public function invite(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:admin,operario'
        ]);

        $farm_id = session('current_farm_id');
        if (!$farm_id) {
            return redirect()->back()->with('error', 'No se pudo identificar la granja actual');
        }

        $farm = Farm::findOrFail($farm_id);
        $user = User::where('email', $request->email)->firstOrFail();

        // Verificar si el usuario ya está invitado o es miembro
        if ($farm->users()->where('users.id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Este usuario ya está invitado a la granja');
        }

        // Verificar si ya existe una invitación pendiente
        $existingInvitation = Invitation::where('email', $request->email)
            ->where('farm_id', $farm_id)
            ->where('accepted', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return redirect()->back()->with('error', 'Ya existe una invitación pendiente para este usuario');
        }

        // Crear nueva invitación
        $invitation = Invitation::create([
            'email' => $request->email,
            'farm_id' => $farm_id,
            'role' => $request->role,
            'token' => \Str::random(32),
            'expires_at' => Carbon::now()->addDays(7),
            'accepted' => false
        ]);

        // Enviar correo de invitación
        try {
            Mail::to($request->email)->send(new InvitationMail($invitation));
            return redirect()->back()->with('success', 'Invitación enviada exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al enviar invitación: ' . $e->getMessage());
            $invitation->delete();
            return redirect()->back()->with('error', 'Error al enviar la invitación. Por favor, intente nuevamente.');
        }
    }

    public function remove(Request $request, Farm $farm, User $user)
    {
        $farm_id = session('current_farm_id');
        if (!$farm_id || $farm->id != $farm_id) {
            return redirect()->back()->with('error', 'No se pudo identificar la granja actual');
        }

        $farm->users()->detach($user->id);
        return redirect()->back()->with('success', 'Usuario removido exitosamente');
    }
}
