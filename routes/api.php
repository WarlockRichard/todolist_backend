<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('logout', 'AuthController@logout');
Route::post('me', 'AuthController@me');
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::apiResource('todos', 'ToDoController', ["only" => ['index', 'store', 'update', 'destroy']]);
