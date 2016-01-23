<?php

// 登录
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController'
]);

// 前台
Route::get('/', 'WelcomeController@index');