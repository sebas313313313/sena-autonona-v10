<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Controlador para gestionar la autenticación de usuarios
 * Maneja las operaciones de inicio de sesión, cierre de sesión y visualización del formulario de login
 */
class LoginController extends Controller
{
    /**
     * La ruta de redirección después del login
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Muestra el formulario de inicio de sesión
     * @return \Illuminate\View\View Vista del formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesa el intento de inicio de sesión
     * @param Request $request Datos del formulario de login
     * @return \Illuminate\Http\RedirectResponse Redirección a home o back con mensaje
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home')
                ->with('success', '¡Bienvenido ' . Auth::user()->name . '!');
        }

        \Log::info('Intento de login fallido para email: ' . $request->email);
        
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Las credenciales proporcionadas no coinciden con nuestros registros.');
    }

    /**
     * Cierra la sesión del usuario actual
     * @param Request $request Solicitud actual
     * @return \Illuminate\Http\RedirectResponse Redirección a la página de login
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->with('success', 'Has cerrado sesión correctamente.');
    }

    public function recoverPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'recovery_question' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un usuario con ese correo electrónico'
            ]);
        }

        // Verificar la pregunta de recuperación
        if ($user->recovery_question !== $request->recovery_question) {
            return response()->json([
                'success' => false,
                'message' => 'La respuesta a la pregunta de recuperación no es correcta'
            ]);
        }

        // Actualizar la contraseña
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente'
        ]);
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)
                    ->join('users_roles', 'users.id', '=', 'users_roles.user_id')
                    ->join('passwords', 'users_roles.id', '=', 'passwords.users_role_id')
                    ->select('users.*', 'passwords.pregunta as recovery_question')
                    ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró un usuario con ese correo electrónico'
            ]);
        }

        return response()->json([
            'success' => true,
            'question' => $user->recovery_question
        ]);
    }

    public function checkAnswer(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'answer' => 'required'
        ]);

        $user = User::where('users.email', $request->email)
                    ->join('users_roles', 'users.id', '=', 'users_roles.user_id')
                    ->join('passwords', 'users_roles.id', '=', 'passwords.users_role_id')
                    ->select('users.*', 'passwords.respuesta as recovery_answer')
                    ->first();

        if (!$user || !Hash::check($request->answer, $user->recovery_answer)) {
            return response()->json([
                'success' => false,
                'message' => 'La respuesta es incorrecta'
            ]);
        }

        // Generar token único
        $token = Str::random(60);
        
        // Guardar el token en la base de datos
        $user->password_reset_token = $token;
        $user->password_reset_expires_at = now()->addHours(1); // El token expira en 1 hora
        $user->save();

        // Enviar correo con Laravel Mail
        try {
            Mail::send(
                'emails.reset-password',
                ['token' => $token, 'name' => $user->name],
                function($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Recuperación de Contraseña - AGROVIDA')
                            ->from(config('mail.from.address'), config('mail.from.name'));
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Se ha enviado un enlace de recuperación a tu correo electrónico'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error enviando correo de recuperación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al enviar el correo. Por favor, intenta nuevamente.'
            ], 500);
        }
    }

    public function showResetForm(Request $request, $token)
    {
        $user = User::where('password_reset_token', $token)
                    ->where('password_reset_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')
                           ->with('error', 'El enlace de recuperación es inválido o ha expirado');
        }

        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::where('password_reset_token', $request->token)
                    ->where('password_reset_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return back()->withErrors(['error' => 'El enlace de recuperación es inválido o ha expirado']);
        }

        $user->password = Hash::make($request->password);
        $user->password_reset_token = null;
        $user->password_reset_expires_at = null;
        $user->save();

        return redirect()->route('login')
                        ->with('success', 'Tu contraseña ha sido actualizada correctamente');
    }
}
