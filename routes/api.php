<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\Users_RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IdentificationTypeController;

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
});
