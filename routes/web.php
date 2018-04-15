<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
	return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function() use ($router){

	//user auth
	$router->group(['prefix' => 'auth'], function($app){
		$app->post('/create', 'UsersController@register');
		$app->post('/login','UsersController@authenticate');
	});

	//user details
	$router->group(['prefix' => 'users', 'middleware' => 'auth'], function($app){
		$app->get('/index', 'UsersController@index');
		$app->get('/role/{role}', 'UsersController@role');
		$app->get('/single-user/{id}', 'UsersController@singleUser');
		$app->put('/forgotpassword','UsersController@forgotpassword');
		$app->delete('/delete/{id}', 'UsersController@delete');
	});

	//article
	$router->group(['prefix' => 'article', 'middleware' => 'auth'], function($app){
		$app->put('/update/{id}', 'articleController@update');
		$app->get('/index', 'articleController@adminart');
		$app->get('/single/{id}', 'articleController@singleById');
		$app->post('/create', 'articleController@create');
		$app->delete('/delete/{id}', 'articleController@delete');
	});

	$router->get('/index', 'articleController@index');
	$router->get('/{slug}', 'articleController@singleArticle');
});
