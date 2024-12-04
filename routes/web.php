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
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Rutas de granjas
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

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
