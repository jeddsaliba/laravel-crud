<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\User\UserController;
use App\Http\Middleware\HashIdMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'auth'], function() {
    Route::post('/login', [AuthController::class, 'login']);
});
Route::group(['middleware' => [
    'auth:sanctum',
    'ability:'.implode(',', config('abilities'))
    ]
], function() {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
    Route::group(['prefix' => 'project', 'middleware' => [HashIdMiddleware::class]], function() {
        Route::get('/list', [ProjectController::class, 'list']);
        Route::group(['prefix' => 'view'], function() {
            Route::get('/{id}', [ProjectController::class, 'view']);
            Route::get('/{id}/task', [ProjectController::class, 'taskList']);
        });
        Route::post('/create', [ProjectController::class, 'create']);
        Route::put('/update/{id}', [ProjectController::class, 'update']);
        Route::delete('/delete/{id}', [ProjectController::class, 'delete']);
    });
    Route::group(['prefix' => 'user'], function() {
        Route::get('/list', [UserController::class, 'list']);
        Route::post('/create', [UserController::class, 'create']);
        Route::put('/update/{id}', [UserController::class, 'update']);
        Route::delete('/delete/{id}', [UserController::class, 'delete']);
    });
});