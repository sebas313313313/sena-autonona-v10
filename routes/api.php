<?php

use App\Http\Controllers\CalibrationController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\ComponentTaskController;
use App\Http\Controllers\FarmComponentController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\Users_RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IdentificationTypeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SensorComponentController;
use App\Http\Controllers\SampleController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->group(function () {
    Route::apiResource('municipalities', MunicipalityController::class);
    Route::apiResource('passwords', PasswordController::class);
    Route::post('passwords/{password}/verify-answer', [PasswordController::class, 'verifyAnswer']);
    
    Route::apiResource('users-roles', Users_RoleController::class);
    Route::post('users-roles/{users_role}/validate-password', [Users_RoleController::class, 'validatePassword']);
    
    Route::apiResource('users', UserController::class);
    Route::apiResource('identification-types', IdentificationTypeController::class);
    Route::apiResource('jobs', JobController::class);
    Route::apiResource('farms', FarmController::class);
    Route::apiResource('sensors', SensorController::class);
});

/*
|   Con estas rutas manejas las distintas peticiones http que podemos hacer desde postman como update,delete o show.
|   Ya que con dichas rutas creamos tambien un CRUD, el cual desde peticiones http mediante nuestro cliente (postman)
|   podemos interactuar con nuestra BD.
*/

// RUTAS_CALIBRATION (HAIVER VELASCO)
Route::prefix('calibration')->group(function () {
    Route::get('/index', [CalibrationController::class, 'index']);
    Route::post('/create', [CalibrationController::class, 'create']);
    Route::get('/show/{id}', [CalibrationController::class, 'show']);
    Route::put('/update/{calibration}', [CalibrationController::class, 'update']);
    Route::delete('/destroy/{calibration}', [CalibrationController::class, 'destroy']);
});

// RUTAS_COMPONENT (HAIVER VELASCO)
Route::prefix('component')->group(function () {
    Route::get('/index', [ComponentController::class, 'index']);
    Route::post('/create', [ComponentController::class, 'create']);
    Route::get('/show/{id}', [ComponentController::class, 'show']);
    Route::put('/update/{component}', [ComponentController::class, 'update']);
    Route::delete('/destroy/{component}', [ComponentController::class, 'destroy']);
});

// RUTAS_COMPONENT_TASK (HAIVER VELASCO)
Route::prefix('component_task')->group(function () {
    Route::get('/index', [ComponentTaskController::class, 'index']);
    Route::post('/create', [ComponentTaskController::class, 'create']);
    Route::get('/show/{id}', [ComponentTaskController::class, 'show']);
    Route::put('/update/{component_task}', [ComponentTaskController::class, 'update']);
    Route::delete('/destroy/{component_task}', [ComponentTaskController::class, 'destroy']);
});

// RUTAS_FARM_COMPONENT (HAIVER VELASCO)
Route::prefix('farm_component')->group(function () {
    Route::get('/index', [FarmComponentController::class, 'index']);
    Route::post('/create', [FarmComponentController::class, 'create']);
    Route::get('/show/{id}', [FarmComponentController::class, 'show']);
    Route::put('/update/{farm_component}', [FarmComponentController::class, 'update']);
    Route::delete('/destroy/{farm_component}', [FarmComponentController::class, 'destroy']);
});

// RUTAS_SENSOR_COMPONENT
Route::prefix('sensor_component')->group(function () {
    Route::get('/index', [SensorComponentController::class, 'index']);
    Route::post('/create', [SensorComponentController::class, 'create']);
    Route::get('/show/{id}', [SensorComponentController::class, 'show']);
    Route::put('/update/{sensor_component}', [SensorComponentController::class, 'update']);
    Route::delete('/destroy/{sensor_component}', [SensorComponentController::class, 'destroy']);
});

// RUTAS_SAMPLE
Route::prefix('sample')->group(function () {
    Route::get('/index', [SampleController::class, 'index']);
    Route::post('/create', [SampleController::class, 'store']);
    Route::get('/show/{sample}', [SampleController::class, 'show']);
    Route::put('/update/{sample}', [SampleController::class, 'update']);
    Route::delete('/destroy/{sample}', [SampleController::class, 'destroy']);
});
