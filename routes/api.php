<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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

Route::namespace('Todo')
    ->middleware(['api', 'auth:api'])
    ->prefix('todos')
    ->group(function () {
        Route::get('/', [TodoController::class, 'getTodos']);
        Route::post('/', [TodoController::class, 'createTodo']);
        Route::put('/{todoId}', [TodoController::class, 'updateTodo']);
        Route::get('/{todoId}', [TodoController::class, 'getTodoItems']);
        Route::delete('/{todoId}', [TodoController::class, 'deleteTodo']);
        Route::post('/{todoId}/item', [TodoController::class, 'createItem']);
        Route::put('/{todoId}/item/{itemId}', [TodoController::class, 'updateItem']);
        Route::delete('/{todoId}/item/{itemId}', [TodoController::class, 'deleteItem']);
    });

Route::namespace('Auth')
    ->middleware([])
    ->prefix('auth')
    ->group(function () {
        Route::post('login', [AuthController::class, 'login']);
    });

Route::namespace('Auth')
    ->middleware(['api', 'auth:api'])
    ->prefix('auth')
    ->group(function () {
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
        Route::get('token', [AuthController::class, 'getTokenStatus']);
    });
