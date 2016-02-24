<?php

//登录
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController'
]);

//前台
Route::get('/', 'WelcomeController@index');
Route::controller('user', 'UserController');
Route::resource('category', 'CategoryController',['only'=>['index','show']]);
Route::resource('article', 'ArticleController',['only'=>['index','show']]);
Route::resource('person', 'PersonController',['only'=>['index','show']]);
Route::get('project/type/{id}', 'ProjectController@getType');
Route::resource('project', 'ProjectController',['only'=>['index','show']]);
Route::get('page/{id}', 'PageController@show');

//管理系统
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware'=>['auth','isadmin']], function () {
    Route::get('', 'DashController@index');
    Route::controller('categories', 'CategoriesController');
    Route::controller('articles', 'ArticlesController');
    Route::controller('system', 'SystemController');
    Route::controller('pages', 'PagesController');
    Route::controller('users', 'UsersController');
    Route::controller('persons', 'PersonsController');
    Route::controller('projects', 'ProjectsController');
});