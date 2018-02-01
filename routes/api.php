<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('logout', 'AuthController@logout');
Route::get('me', 'AuthController@me');
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::apiResource('todos', 'TodoController', [
    "only" => ['index', 'store', 'update', 'destroy']
])->middleware('auth:api');

Route::apiResource('permissions', 'PermissionController', [
        "only" => ['index', 'store', 'update', 'destroy']
])->middleware('auth:api');
