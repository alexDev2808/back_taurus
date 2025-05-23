<?php

use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\DepartamentController;
use App\Http\Controllers\Api\SubareaController;
use App\Http\Controllers\Api\SubsidiaryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:api')->group(function () {
            Route::get('me', [AuthController::class, 'me']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::resource('users', UserController::class);
        Route::apiResource('subsidiaries', SubsidiaryController::class);
        Route::get('subsidiaries/{subsidiary}/areas', [SubsidiaryController::class, 'areas']);
        Route::apiResource('areas', AreaController::class);
        Route::get('areas/{area}/subareas', [AreaController::class, 'subareas']);
        Route::apiResource('subareas', SubareaController::class);
        Route::get('subareas/{subarea}/departaments', [SubareaController::class, 'departaments']);
        Route::apiResource('departaments', DepartamentController::class);
        Route::get('departaments/{departament}/users', [DepartamentController::class, 'users']);
    });


});


Route::get('/', [AuthController::class, 'unauthorized'])->name('login');