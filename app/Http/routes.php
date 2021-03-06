<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin Authentication Routes...
Route::group(['prefix' => config('admin.url')], function() {
    Route::group(['middleware' => 'admin.auth'], function() {
        Route::get('/', ['as' => 'admin', 'uses' => 'Admin\HomeController@index']);
        Route::get('/home', ['as' => 'admin', 'uses' => 'Admin\HomeController@index']);
        Route::get('logout', ['as' => 'admin.logout', 'uses' => 'Admin\AuthController@logout']);
    });

    Route::group(['middleware' => 'admin.guest'], function() {

        // Authentication Routes...
        Route::get('login', ['as' => 'admin.login', 'uses' => 'Admin\AuthController@showLoginForm']);
        Route::post('login', ['as' => 'admin.login.post', 'uses' => 'Admin\AuthController@login']);

        // Password Reset Routes...
        Route::get('password/reset/{token?}', ['as' => 'admin.password.reset', 'uses' => 'Admin\PasswordController@showResetForm']);
        Route::post('password/email', ['as' => 'admin.password.email', 'uses' => 'Admin\PasswordController@sendResetLinkEmail']);
        Route::post('password/reset', ['as' => 'admin.password.post', 'uses' => 'Admin\PasswordController@reset']);
    });
});
