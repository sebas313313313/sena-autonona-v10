<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Password;
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
            
            // Asegurarse de que el token CSRF se regenere
            $request->session()->regenerateToken();
            
            return redirect()->intended('/')
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

    /**
     * Verifica si el correo electrónico existe y devuelve la pregunta de seguridad
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->userRole) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró ninguna cuenta con este correo electrónico.'
            ]);
        }

        // Obtener la última pregunta de seguridad del usuario
        $password = Password::where('users_role_id', $user->userRole->id)
                          ->latest('fecha')
                          ->first();

        if (!$password || !$password->pregunta) {
            return response()->json([
                'success' => false,
                'message' => 'No hay una pregunta de seguridad configurada para esta cuenta.'
            ]);
        }

        return response()->json([
            'success' => true,
            'question' => $password->pregunta
        ]);
    }

    public function checkAnswer(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'answer' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->userRole) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }

        $password = Password::where('users_role_id', $user->userRole->id)
                          ->latest('fecha')
                          ->first();

        if (!$password || !Hash::check($request->answer, $password->respuesta)) {
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

        // Enviar correo con el enlace de recuperación
        Mail::send('emails.reset-password', [
            'token' => $token,
            'name' => $user->name
        ], function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Recuperación de Contraseña - AGROVIDA');
        });

        return response()->json([
            'success' => true,
            'message' => 'Se ha enviado un enlace de recuperación a tu correo electrónico'
        ]);
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
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('password_reset_token', $request->token)
                    ->where('password_reset_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return back()->with('error', 'El token es inválido o ha expirado');
        }

        $user->password = Hash::make($request->password);
        $user->password_reset_token = null;
        $user->password_reset_expires_at = null;
        $user->save();

        return redirect()->route('login')
                        ->with('success', 'Tu contraseña ha sido actualizada correctamente');
    }
}
