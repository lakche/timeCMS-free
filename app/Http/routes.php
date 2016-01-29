<?php

// 登录
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController'
]);

// 前台
Route::get('/', 'WelcomeController@index');
Route::controller('user', 'UserController');
Route::resource('category', 'CategoryController',['only'=>['index','show']]);
Route::resource('article', 'ArticleController',['only'=>['index','show']]);

//管理系统
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware'=>['auth','isadmin']], function () {
    Route::get('', 'DashController@index');
    Route::controller('categories', 'CategoriesController');
    Route::controller('articles', 'ArticlesController');
    Route::controller('system', 'SystemController');
});