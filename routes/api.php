<?php

use App\Http\Controllers\CalibrationController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\ComponentTaskController;
use App\Http\Controllers\FarmComponentController;

use App\Http\Controllers\MunicipalityController;

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
