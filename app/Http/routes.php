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
    Route::controller('categories', 'CategoriesController',[
        'getIndex' => 'admin.categories',
        'getSubs' => 'admin.categories.subs',
        'getAdd' => 'admin.categories.add',
        'getEdit' => 'admin.categories.edit',
        'postSave' => 'admin.categories.save',
        'postSaveCover' => 'admin.categories.savecover',
        'postDelete' => 'admin.categories.delete',
    ]);
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
    Route::controller('system', 'SystemController',[
        'getIndex' => 'admin.system',
        'postSave' => 'admin.system.save',
    ]);
    Route::controller('pages', 'PagesController',[
        'getIndex' => 'admin.pages',
        'getAdd' => 'admin.pages.add',
        'getEdit' => 'admin.pages.edit',
        'postSave' => 'admin.pages.save',
        'postSaveCover' => 'admin.pages.savecover',
        'postDelete' => 'admin.pages.delete',
    ]);
    Route::controller('users', 'UsersController',[
        'getIndex' => 'admin.users',
        'postDelete' => 'admin.users.delete',
        'postAdmin' => 'admin.users.admin',
    ]);
    Route::controller('persons', 'PersonsController');
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