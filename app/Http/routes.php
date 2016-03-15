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
Route::get('page/{id}', ['as' => 'page.show'], 'PageController@show');

//管理系统
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware'=>['auth','isadmin']], function () {
    Route::get('', 'DashController@index');
    Route::resource('attachment', 'AttachmentController',['only'=>['store']]);
    Route::resource('category', 'CategoryController');
    Route::controller('articles', 'ArticlesController',[
        'getIndex' => 'admin.articles',
        'getType' => 'admin.articles.type',
        'getAdd' => 'admin.articles.add',
        'getEdit' => 'admin.articles.edit',
        'postSave' => 'admin.articles.save',
        'postUpdateImage' => 'admin.articles.updateimage',
        'postSaveCover' => 'admin.articles.savecover',
        'postDelete' => 'admin.articles.delete',
    ]);
    Route::resource('system', 'SystemController',['only'=>['index','store']]);
    Route::resource('pages', 'PagesController');
    Route::resource('users', 'UsersController',['only'=>['index','update','destroy']]);
    Route::controller('persons', 'PersonsController',[
        'getIndex' => 'admin.persons',
        'getAdd' => 'admin.persons.add',
        'getEdit' => 'admin.persons.edit',
        'postSave' => 'admin.persons.save',
        'postUpdateImage' => 'admin.persons.updateimage',
        'postSaveCover' => 'admin.persons.savecover',
        'postDelete' => 'admin.persons.delete',
    ]);
    Route::controller('projects', 'ProjectsController',[
        'getIndex' => 'admin.projects',
        'getType' => 'admin.projects.type',
        'getAdd' => 'admin.projects.add',
        'getEdit' => 'admin.projects.edit',
        'postSave' => 'admin.projects.save',
        'postUpdateImage' => 'admin.projects.updateimage',
        'postSaveCover' => 'admin.projects.savecover',
        'postDelete' => 'admin.projects.delete',
    ]);
});