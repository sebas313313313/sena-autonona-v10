<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Models\Municipality;
use App\Models\IdentificationType;
use App\Models\FarmType;
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
    
    // Obtener las granjas invitadas (excluyendo las propias)
    $invitedFarms = $user->farms()
        ->whereNotIn('farms.id', $farms->pluck('id'))
        ->with('municipality')
        ->get();

    // Obtener los componentes y usarlos como tipos de granja
    $components = \App\Models\Component::all();
    $farmTypes = $components->pluck('description', 'id')->toArray();

    // Obtener los sensores asociados a cada componente
    $sensorsByComponent = [];
    foreach ($components as $component) {
        // Mapear los nombres de componentes a farm_type
        $farmTypeMap = [
            'Acuaponia' => 'acuaponica',
            'Hidroponia' => 'hidroponica',
            'Sistema de Riego' => 'riego',
            'Sistema de Vigilancia' => 'vigilancia'
        ];
        
        // Obtener el farm_type correspondiente
        $farmType = $farmTypeMap[$component->description] ?? null;
        
        if ($farmType) {
            // Obtener los sensores activos para este tipo de granja
            $sensors = \App\Models\Sensor::where('farm_type', $farmType)
                                       ->where('estado', 'activo')
                                       ->get();

            $sensorsByComponent[$component->id] = $sensors->pluck('description')->toArray();
        } else {
            $sensorsByComponent[$component->id] = [];
        }
    }

    return view('farms.index', [
        'farms' => $farms,
        'invitedFarms' => $invitedFarms,
        'municipalities' => \App\Models\Municipality::all(),
        'components' => $components,
        'farmTypes' => $farmTypes,
        'sensorsByComponent' => $sensorsByComponent
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

// Rutas para SuperD
Route::prefix('superD')->group(function () {
    Route::get('/login', [App\Http\Controllers\SuperDController::class, 'showLoginForm'])->name('superD.login');
    Route::post('/login', [App\Http\Controllers\SuperDController::class, 'login'])->name('superD.login.submit');
    Route::post('/logout', [App\Http\Controllers\SuperDController::class, 'logout'])->name('superD.logout');
    Route::get('/dashboard', [App\Http\Controllers\SuperDController::class, 'dashboard'])->name('superD.dashboard')->middleware('auth');
    Route::post('/users/{id}/change-password', [App\Http\Controllers\SuperDController::class, 'changePassword'])->name('superD.users.changePassword')->middleware('auth');
    Route::post('/create-superd', [App\Http\Controllers\SuperDController::class, 'createSuperD'])->name('superD.create')->middleware('auth');
    Route::delete('/users/{id}', [App\Http\Controllers\SuperDController::class, 'deleteUser'])->name('superD.users.delete')->middleware('auth');
    Route::get('/users', [App\Http\Controllers\SuperDController::class, 'getUsers'])->name('superD.users.list')->middleware('auth');
    Route::get('/users/{id}', [App\Http\Controllers\SuperDController::class, 'getUserDetails'])->name('superD.users.details')->middleware('auth');
    Route::delete('/components/{component}', [App\Http\Controllers\SuperDController::class, 'deleteComponent'])->name('superD.components.delete');
    Route::get('/components/{component}/sensors', [App\Http\Controllers\SuperDController::class, 'getComponentSensors'])->name('superD.components.sensors');
    
    // Rutas para preguntas de seguridad
    Route::get('/security-questions', [App\Http\Controllers\SecurityQuestionController::class, 'index'])->name('security-questions.index');
    Route::post('/security-questions', [App\Http\Controllers\SecurityQuestionController::class, 'store'])->name('security-questions.store');
    Route::put('/security-questions/{question}', [App\Http\Controllers\SecurityQuestionController::class, 'update'])->name('security-questions.update');
    Route::delete('/security-questions/{question}', [App\Http\Controllers\SecurityQuestionController::class, 'destroy'])->name('security-questions.destroy');
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

    // Invitaciones (fuera del middleware farm.access)
    Route::post('/invitations/send', [App\Http\Controllers\InvitationController::class, 'send'])->name('invitations.send');
    Route::get('/invitations/accept/{token}', [App\Http\Controllers\InvitationController::class, 'accept'])->name('invitations.accept');

    // Ruta para actualizar el nombre de usuario
    Route::post('/user/update-name', [App\Http\Controllers\UserController::class, 'updateName'])->name('user.update.name');

    // Actualización de estado de sensores (fuera del middleware farm.access)
    Route::post('/sensores/{id}/estado', [App\Http\Controllers\SensorController::class, 'updateEstado'])
        ->name('sensores.updateEstado');

    Route::middleware(['auth'])->group(function () {
        Route::post('/components', [App\Http\Controllers\ComponentController::class, 'store'])->name('components.store');
        Route::put('/components/{component}', [App\Http\Controllers\ComponentController::class, 'update'])->name('components.update');
        Route::get('/components/{component}/sensors', [App\Http\Controllers\SensorController::class, 'index'])->name('sensors.index');
        Route::post('/components/{component}/sensors', [App\Http\Controllers\SensorController::class, 'store'])->name('sensors.store');
    });

    /**
     * Grupo de rutas protegidas
     * Requieren acceso a granja
     * Incluye gestión de granjas, dashboard y funcionalidades principales
     */
    Route::middleware('farm.access')->group(function () {
        // Dashboard y tablero
        Route::get('/dashboard/{farm_id}', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.home');
        
        // Sensores
        Route::get('/sensores/{farm_id}', [App\Http\Controllers\SensorController::class, 'farmSensors'])->name('sensores.index');
        
        // Usuarios
        Route::get('/dashboard/farm/{farm_id}/users', [DashboardController::class, 'users'])->name('dashboard.users');
        Route::delete('/dashboard/farm/{farm_id}/unlink/{user_id}', [DashboardController::class, 'unlinkUser'])->name('dashboard.unlink.user');

        // Ruta de sensores
        Route::get('/sensores/{farm_id}', function ($farm_id) {
            $farm = \App\Models\Farm::findOrFail($farm_id);
            
            // Obtener los sensores a través de las relaciones
            $sensors = \App\Models\Sensor::whereHas('sensor_components', function($query) use ($farm_id) {
                $query->whereHas('farm_component', function($q) use ($farm_id) {
                    $q->where('farm_id', $farm_id);
                });
            })->select('id', 'description as nombre', 'estado')->get();
            
            return view('dashboard.sensores.index', [
                'farm' => $farm,
                'sensors' => $sensors
            ]);
        })->name('sensores.index');

        // Rutas de tareas
        Route::get('/tasks/{farm_id}', [App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
        Route::post('/tasks/{farm_id}', [App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
        Route::put('/tasks/{farm_id}/{task}', [App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{farm_id}/{task}', [App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy')->where('task', '[0-9]+');

        // Rutas de usuarios
        Route::get('/users/{farm_id}', function ($farm_id) {
            $farm = \App\Models\Farm::findOrFail($farm_id);
            
            // Obtener el dueño de la granja
            $owner = $farm->usersRole->user;
            
            // Obtener los usuarios invitados
            $users = $farm->users()->get();
            
            // Agregar el dueño a la lista de usuarios
            $users->prepend($owner);
            
            return view('dashboard.tables.index', [
                'farm' => $farm,
                'owner' => $owner,
                'users' => $users
            ]);
        })->name('users.index');

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

// Ruta para obtener los municipios
Route::get('/api/municipalities', [App\Http\Controllers\MunicipalityController::class, 'index'])->name('municipalities.index');
