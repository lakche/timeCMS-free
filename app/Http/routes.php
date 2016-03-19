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
Route::get('page/{id}', ['as' => 'page.show' ,'uses' => 'PageController@show']);

//管理系统
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware'=>['auth','isadmin']], function () {
    Route::get('', 'DashController@index');
    Route::resource('attachment', 'AttachmentController',['only'=>['store']]);
    Route::resource('category', 'CategoryController');
    Route::resource('articles', 'ArticlesController');
    Route::resource('system', 'SystemController',['only'=>['index','store']]);
    Route::resource('pages', 'PagesController');
    Route::resource('users', 'UsersController',['only'=>['index','update','destroy']]);
    Route::resource('persons', 'PersonsController');
    Route::resource('projects', 'ProjectsController');
});