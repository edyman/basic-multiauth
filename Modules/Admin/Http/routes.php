<?php
Route::group(
    ['middleware' => 'web', 'prefix' => 'admin',
        'namespace' => 'Modules\Admin\Http\Controllers'],
    function()
    {
        Route::group(['middleware' => 'admin.auth'], function() {
            Route::get('/', ['as' => 'admin', 'uses' => 'HomeController@index']);
            Route::get('/home', ['as' => 'admin.home', 'uses' => 'HomeController@index']);
            Route::get('logout', ['as' => 'admin.logout', 'uses' => 'AuthController@logout']);
        });

        Route::group(['middleware' => 'admin.guest'], function() {

            // Authentication Routes...
            Route::get('login', ['as' => 'admin.login', 'uses' => 'AuthController@showLoginForm']);
            Route::post('login', ['as' => 'admin.login.post', 'uses' => 'AuthController@login']);

            // Password Reset Routes...
            Route::get('password/reset/{token?}', ['as' => 'admin.password.reset', 'uses' => 'PasswordController@showResetForm']);
            Route::post('password/email', ['as' => 'admin.password.email', 'uses' => 'PasswordController@sendResetLinkEmail']);
            Route::post('password/reset', ['as' => 'admin.password.post', 'uses' => 'PasswordController@reset']);
        });
    });