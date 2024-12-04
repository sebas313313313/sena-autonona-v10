<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
