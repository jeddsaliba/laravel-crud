<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Chart\ChartController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\ProjectTask\ProjectTaskController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\User\UserController;
use App\Http\Middleware\{
    HashId\HashIdMiddleware,
    Project\ProjectMiddleware,
    ProjectTask\ProjectTaskMiddleware
};
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
        Route::get('/user', [AuthController::class, 'user']);
    });
    Route::group(['middleware' => [HashIdMiddleware::class]], function() {
        Route::group(['prefix' => 'chart'], function() {
            Route::get('/status', [ChartController::class, 'status']);
            Route::get('/top-performers', [ChartController::class, 'topPerformers']);
            Route::get('/performance', [ChartController::class, 'performance']);
        });
        Route::group(['prefix' => 'project', 'middleware' => [ProjectMiddleware::class]], function() {
            Route::get('/list', [ProjectController::class, 'list']);
            Route::get('/view/{id}', [ProjectController::class, 'view']);
            Route::post('/create', [ProjectController::class, 'create']);
            Route::put('/update/{id}', [ProjectController::class, 'update']);
            Route::delete('/delete/{id}', [ProjectController::class, 'delete']);
        });
        Route::group(['prefix' => 'task/{projectID}', 'middleware' => [ProjectTaskMiddleware::class]], function() {
            Route::get('/list', [ProjectTaskController::class, 'list']);
            Route::get('/view/{id}', [ProjectTaskController::class, 'view']);
            Route::post('/create', [ProjectTaskController::class, 'create']);
            Route::put('/update/{id}', [ProjectTaskController::class, 'update']);
            Route::delete('/delete/{id}', [ProjectTaskController::class, 'delete']);
        });
        Route::group(['prefix' => 'user'], function() {
            Route::get('/list', [UserController::class, 'list']);
            Route::post('/create', [UserController::class, 'create']);
            Route::put('/update/{id}', [UserController::class, 'update']);
            Route::delete('/delete/{id}', [UserController::class, 'delete']);
        });
        Route::group(['prefix' => 'report'], function() {
            Route::get('/project', [ReportController::class, 'project']);
            Route::get('/project-task', [ReportController::class, 'projectTask']);
        });
    });
});