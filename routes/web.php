<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Municipality;
use App\Models\IdentificationType;

// Ruta raíz
Route::get('/', function () {
    if (auth()->check()) {
        $userRole = \App\Models\Users_Role::where('user_id', auth()->id())->first();
        $farms = $userRole ? \App\Models\Farm::where('users_role_id', $userRole->id)->get() : collect();
        
        return view('farms.index', [
            'farms' => $farms,
            'municipalities' => \App\Models\Municipality::all()
        ]);
    }
    return redirect()->route('login');
})->name('home');

// Rutas de autenticación
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

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth', 'farm.access'])->group(function () {
    // Rutas de granjas
    Route::get('/farms', [App\Http\Controllers\FarmController::class, 'index'])->name('farms.index');
    Route::post('/farms', [App\Http\Controllers\FarmController::class, 'store'])->name('farms.store');
    Route::delete('/farms/{farm}', [App\Http\Controllers\FarmController::class, 'destroy'])->name('farms.destroy');
    
    // Dashboard
    Route::get('/tablero/{farm_id}', function ($farm_id) {
        $farm = \App\Models\Farm::findOrFail($farm_id);
        return view('dashboard.index', compact('farm'));
    })->name('dashboard');

    // Widgets
    Route::get('/widgets', function () {
        return view('dashboard.widgets.index');
    })->name('widgets');

    // UI Elements
    Route::get('/ui', function () {
        return view('dashboard.ui.index');
    })->name('ui');

    // Forms
    Route::get('/forms', function () {
        return view('dashboard.forms.index');
    })->name('forms');

    // Charts
    Route::get('/charts', function () {
        return view('dashboard.charts.index');
    })->name('charts');

    // Tables
    Route::get('/tables', function () {
        return view('dashboard.tables.index');
    })->name('tables');

    // Ruta de prueba para verificar el envío de correos
    Route::get('/test-mail', function () {
        try {
            Mail::raw('Prueba de correo desde AGROVIDA', function($message) {
                $message->to('test@example.com')
                        ->subject('Prueba de Correo')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            return 'Correo enviado correctamente. Revisa tu bandeja de Mailtrap.';
        } catch (\Exception $e) {
            return 'Error al enviar el correo: ' . $e->getMessage();
        }
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
