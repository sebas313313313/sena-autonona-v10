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

// RUTAS_MUNICIPALITY (SEBAS)
Route::prefix('municipality')->group(function () {
    Route::get('/index', [MunicipalityController::class, 'index']);
    Route::post('/create', [MunicipalityController::class, 'store']);
    Route::get('/show/{municipality}', [MunicipalityController::class, 'show']);
    Route::put('/update/{municipality}', [MunicipalityController::class, 'update']);
    Route::delete('/destroy/{municipality}', [MunicipalityController::class, 'destroy']);
});

// RUTAS_PASSWORD (SEBAS)
Route::prefix('password')->group(function () {
    Route::get('/index', [PasswordController::class, 'index']);
    Route::post('/create', [PasswordController::class, 'store']);
    Route::get('/show/{password}', [PasswordController::class, 'show']);
    Route::put('/update/{password}', [PasswordController::class, 'update']);
    Route::delete('/destroy/{password}', [PasswordController::class, 'destroy']);
    Route::post('/{password}/verify-answer', [PasswordController::class, 'verifyAnswer']);
});

// RUTAS_USERS_ROLE (SEBAS)
Route::prefix('users_role')->group(function () {
    Route::get('/index', [Users_RoleController::class, 'index']);
    Route::post('/create', [Users_RoleController::class, 'store']);
    Route::get('/show/{users_role}', [Users_RoleController::class, 'show']);
    Route::put('/update/{users_role}', [Users_RoleController::class, 'update']);
    Route::delete('/destroy/{users_role}', [Users_RoleController::class, 'destroy']);
    Route::post('/{users_role}/validate-password', [Users_RoleController::class, 'validatePassword']);
});

// RUTAS_USER (SEBAS)
Route::prefix('user')->group(function () {
    Route::get('/index', [UserController::class, 'index']);
    Route::post('/create', [UserController::class, 'store']);
    Route::get('/show/{user}', [UserController::class, 'show']);
    Route::put('/update/{user}', [UserController::class, 'update']);
    Route::delete('/destroy/{user}', [UserController::class, 'destroy']);
});

// RUTAS_IDENTIFICATION_TYPE (SEBAS)
Route::prefix('identification_type')->group(function () {
    Route::get('/index', [IdentificationTypeController::class, 'index']);
    Route::post('/create', [IdentificationTypeController::class, 'store']);
    Route::get('/show/{identification_type}', [IdentificationTypeController::class, 'show']);
    Route::put('/update/{identification_type}', [IdentificationTypeController::class, 'update']);
    Route::delete('/destroy/{identification_type}', [IdentificationTypeController::class, 'destroy']);
});

// RUTAS_JOB (SEBAS)
Route::prefix('job')->group(function () {
    Route::get('/index', [JobController::class, 'index']);
    Route::post('/create', [JobController::class, 'store']);
    Route::get('/show/{job}', [JobController::class, 'show']);
    Route::put('/update/{job}', [JobController::class, 'update']);
    Route::delete('/destroy/{job}', [JobController::class, 'destroy']);
});

// RUTAS_FARM (SEBAS)
Route::prefix('farm')->group(function () {
    Route::get('/index', [FarmController::class, 'index']);
    Route::post('/create', [FarmController::class, 'store']);
    Route::get('/show/{farm}', [FarmController::class, 'show']);
    Route::put('/update/{farm}', [FarmController::class, 'update']);
    Route::delete('/destroy/{farm}', [FarmController::class, 'destroy']);
});

// RUTAS_SENSOR (SEBAS)
Route::prefix('sensor')->group(function () {
    Route::get('/index', [SensorController::class, 'index']);
    Route::post('/create', [SensorController::class, 'store']);
    Route::get('/show/{sensor}', [SensorController::class, 'show']);
    Route::put('/update/{sensor}', [SensorController::class, 'update']);
    Route::delete('/destroy/{sensor}', [SensorController::class, 'destroy']);
});

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

// RUTAS_SENSOR_COMPONENT (HAIVER VELASCO)
Route::prefix('sensor_component')->group(function () {
    Route::get('/index', [SensorComponentController::class, 'index']);
    Route::post('/create', [SensorComponentController::class, 'create']);
    Route::get('/show/{id}', [SensorComponentController::class, 'show']);
    Route::put('/update/{sensor_component}', [SensorComponentController::class, 'update']);
    Route::delete('/destroy/{sensor_component}', [SensorComponentController::class, 'destroy']);
});

// RUTAS_SAMPLE (HAIVER VELASCO)
Route::prefix('sample')->group(function () {
    Route::get('/index', [SampleController::class, 'index']);
    Route::post('/create', [SampleController::class, 'store']);
    Route::get('/show/{sample}', [SampleController::class, 'show']);
    Route::put('/update/{sample}', [SampleController::class, 'update']);
    Route::delete('/destroy/{sample}', [SampleController::class, 'destroy']);
});
