<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::pattern('id', '[0-9]+');
Route::pattern('user_id', '[0-9]+');
Route::pattern('todo_id', '[0-9]+');

Route::post('logout', 'AuthController@logout');
Route::post('login', 'AuthController@login');

Route::post('user', 'UserController@store');
Route::prefix('user')->middleware('auth:api')->group(function () {
    Route::get('/', 'UserController@index');

    Route::prefix('{user_id}/todos')->group(function () {
        Route::get('/', 'UserTodosController@index');
        Route::post('/', 'UserTodosController@store');
        Route::put('{todo_id}', 'UserTodosController@update');
        Route::delete('{todo_id}', 'UserTodosController@destroy');
        Route::put('all', 'UserTodosController@updateAll');
        Route::delete('completed', 'UserTodosController@destroyCompleted');
    });
});

Route::apiResource('todos', 'TodoController', [
    "only" => ['index', 'store', 'update', 'destroy']
])->middleware('auth:api');

Route::apiResource('permissions', 'PermissionController', [
        "only" => ['index', 'store', 'update', 'destroy']
])->middleware('auth:api');
