<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::pattern('user', '[0-9]+');

Route::post('logout', 'AuthController@logout');
Route::post('login', 'AuthController@login');

Route::post('user', 'UserController@store');
Route::prefix('user')->middleware('auth:api')->group(function () {
    Route::get('/', 'UserController@index');

    Route::prefix('{user}/todos')->group(function () {
        Route::get('/', 'UserTodosController@index');

        Route::post('/', 'UserTodosController@store');
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
