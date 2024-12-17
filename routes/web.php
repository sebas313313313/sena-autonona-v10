<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Municipality;
use App\Models\IdentificationType;

/**
 * Archivo de Rutas Web
 * 
 * Este archivo define todas las rutas web de la aplicación AGROVIDA.
 * Las rutas están organizadas en grupos según su funcionalidad y requisitos de autenticación.
 */

/**
 * Ruta raíz - Página principal
 * Si el usuario está autenticado, muestra sus granjas propias e invitadas
 * Si no está autenticado, redirige al login
 */
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        $userRole = \App\Models\Users_Role::where('user_id', $user->id)->first();
        
        // Obtener las granjas propias
        $farms = $userRole ? \App\Models\Farm::where('users_role_id', $userRole->id)->get() : collect();
        
        // Obtener las granjas invitadas
        $invitedFarms = $user->farms()->with('municipality')->get();
        
        return view('farms.index', [
            'farms' => $farms,
            'invitedFarms' => $invitedFarms,
            'municipalities' => \App\Models\Municipality::all()
        ]);
    }
    return redirect()->route('login');
})->name('home');

/**
 * Grupo de rutas para usuarios no autenticados
 * Incluye login, registro y recuperación de contraseña
 */
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    
    // Registro
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

    // Recuperación de contraseña
    Route::post('/password/recover', [LoginController::class, 'recoverPassword'])->name('password.recover');
    Route::post('/password/check-email', [LoginController::class, 'checkEmail'])->name('password.check-email');
    Route::post('/password/check-answer', [LoginController::class, 'checkAnswer'])->name('password.check-answer');
    Route::get('/password/reset/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [LoginController::class, 'resetPassword'])->name('password.update');
});

/**
 * Grupo de rutas protegidas
 * Requieren autenticación y acceso a granja
 * Incluye gestión de granjas, dashboard y funcionalidades principales
 */
Route::middleware(['auth', 'farm.access'])->group(function () {
    // Rutas de granjas
    Route::get('/farms', [App\Http\Controllers\FarmController::class, 'index'])->name('farms.index');
    Route::post('/farms', [App\Http\Controllers\FarmController::class, 'store'])->name('farms.store');
    Route::delete('/farms/{farm}', [App\Http\Controllers\FarmController::class, 'destroy'])->name('farms.destroy');
    
    // Dashboard y tablero
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard.home');

    Route::get('/tablero', function () {
        return view('dashboard.tables.index');
    })->name('tablero');

    Route::get('/tablero/{farm_id}', function ($farm_id) {
        $farm = \App\Models\Farm::findOrFail($farm_id);
        return view('dashboard.index', compact('farm'));
    })->name('dashboard');

    // Secciones del dashboard
    Route::get('/widgets', function () {
        return view('dashboard.widgets.index');
    })->name('widgets');

    Route::get('/ui', function () {
        return view('dashboard.ui.index');
    })->name('ui');

    Route::get('/forms', function () {
        return view('dashboard.forms.index');
    })->name('forms');

    Route::get('/charts', function () {
        return view('dashboard.charts.index');
    })->name('charts');

    Route::get('/tables', function () {
        $users = \App\Models\User::all();
        return view('dashboard.tables.index', compact('users'));
    })->name('tables');

    // Sistema de invitaciones
    Route::post('/invitation/send', [App\Http\Controllers\InvitationController::class, 'send'])->name('invitation.send');
    Route::get('/invitation/accept/{token}', [App\Http\Controllers\InvitationController::class, 'accept'])->name('invitation.accept');

    // Cierre de sesión
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
