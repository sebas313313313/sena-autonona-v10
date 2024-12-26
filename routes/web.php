<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Models\Municipality;
use App\Models\IdentificationType;
use Illuminate\Http\Request;

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
    // Si no hay usuarios registrados, redirigir al registro
    if (\App\Models\User::count() === 0) {
        return redirect()->route('register');
    }
    
    // Si no está autenticado, redirigir al login
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    // Si está autenticado, mostrar sus granjas
    $user = auth()->user();
    $userRole = \App\Models\Users_Role::where('user_id', $user->id)->first();
    
    // Obtener las granjas propias
    $farms = $userRole ? \App\Models\Farm::where('users_role_id', $userRole->id)->get() : collect();
    
    // Obtener las granjas invitadas
    $invitedFarms = $user->farms()->with('municipality')->get();
    
    return view('farms.index', [
        'farms' => $farms,
        'invitedFarms' => $invitedFarms,
        'municipalities' => \App\Models\Municipality::all(),
        'components' => \App\Models\Component::all()
    ]);
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
 * Grupo de rutas para usuarios autenticados
 */
Route::middleware('auth')->group(function () {
    // Cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Rutas de granjas
    Route::get('/farms', [App\Http\Controllers\FarmController::class, 'index'])->name('farms.index');
    Route::post('/farms', [App\Http\Controllers\FarmController::class, 'store'])->name('farms.store');
    Route::delete('/farms/{farm}', [App\Http\Controllers\FarmController::class, 'destroy'])->name('farms.destroy');
    
    /**
     * Grupo de rutas protegidas
     * Requieren acceso a granja
     * Incluye gestión de granjas, dashboard y funcionalidades principales
     */
    Route::middleware('farm.access')->group(function () {
        // Dashboard y tablero
        Route::get('/dashboard/{farm_id}', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.home');

        Route::get('/tablero', function () {
            return view('dashboard.tables.index');
        })->name('tablero');

        Route::get('/tablero/{farm_id}', function ($farm_id) {
            $farm = \App\Models\Farm::findOrFail($farm_id);
            $user = auth()->user();
            
            session(['current_farm_id' => $farm_id]);
            
            // Si el usuario es el dueño de la granja
            if ($farm->users_role_id === $user->userRole->id) {
                session(['farm_role' => 'admin']);
            } else {
                // Si el usuario es invitado, obtener su rol de la relación
                $farmUser = $farm->users()->where('users.id', $user->id)->first();
                if ($farmUser) {
                    session(['farm_role' => $farmUser->pivot->role]);
                }
            }
            
            return view('dashboard.index', compact('farm'));
        })->name('dashboard');

        // Rutas para invitar/remover usuarios
        Route::post('/invitation/send', [App\Http\Controllers\InvitationController::class, 'send'])->name('invitation.sendByEmail');
        Route::get('/invitation/accept/{token}', [App\Http\Controllers\InvitationController::class, 'accept'])->name('invitation.accept');
        Route::delete('/farm/{farm}/users/{user}', [App\Http\Controllers\FarmUserController::class, 'remove'])->name('farm.users.remove');

        // Rutas de tareas
        Route::get('/tasks', [App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
        Route::put('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');

        // Rutas de componentes y sensores
        Route::get('/components/{component}/sensors', [App\Http\Controllers\SensorComponentController::class, 'getSensorsByComponent'])->name('components.sensors');

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
            // Obtener el ID de la granja actual desde la sesión
            $farm_id = session('current_farm_id');
            
            // Obtener la granja
            $farm = \App\Models\Farm::findOrFail($farm_id);
            
            // Obtener el dueño de la granja
            $owner = $farm->usersRole->user;
            
            // Obtener los usuarios invitados
            $invitedUsers = $farm->users()->get();
            
            // Combinar el dueño con los usuarios invitados
            $users = collect([$owner])->concat($invitedUsers)->unique('id');
            
            return view('dashboard.tables.index', compact('users', 'owner'));
        })->name('tables');
    });
});
