<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/register', 'Api\UsersController@postRegister');

Route::post('/login', 'Api\UsersController@postLogin');

Route::get('/posts/index', 'Api\PostsController@postsIndex');

Route::group(['middleware' => 'token.auth'], function () {
	Route::get('/member/info', 'Api\UsersController@getMemberInfo');

	Route::get('/my-posts', 'Api\UsersController@getMyPosts');

	Route::post('/posts/create', 'Api\PostsController@postsCreate');

	Route::post('/posts/update/{id}', 'Api\PostsController@postsUpdate')->where(['id' => '[0-9]+']);

	Route::get('/posts/delete/{id}', 'Api\PostsController@postsDelete')->where(['id' => '[0-9]+']);
});
