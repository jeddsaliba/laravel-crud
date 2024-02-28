<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Project\ProjectController;
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
    Route::group(['prefix' => 'project'], function() {
        Route::get('/list', [ProjectController::class, 'list']);
        Route::post('/create', [ProjectController::class, 'create']);
        Route::put('/update/{id}', [ProjectController::class, 'update']);
        Route::delete('/delete/{id}', [ProjectController::class, 'delete']);
    });
});