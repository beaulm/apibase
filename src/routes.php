<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('uses' => 'HomeController@home', 'as' => 'home'));

Route::get('login', array('uses' => 'HomeController@loginform', 'as' => 'login'));

Route::post('login', array('uses' => 'HomeController@login'));

Route::get('logout', array('uses' => 'HomeController@logout', 'as' => 'logout'));

Route::get('password/forgot', array('uses' => 'HomeController@forgotpasswordform'));

Route::get('password/remind', array('uses' => 'HomeController@remindpasswordform'));

Route::post('password/remind', array('uses' => 'HomeController@remindpassword', 'as' => 'remind'));

Route::get('password/reset/{token}', array('uses' => 'HomeController@resetpasswordform'));

Route::post('password/reset/{token}', array('uses' => 'HomeController@resetpassword', 'as' => 'reset'));

Route::any('api/v1/login', array('as' => 'apilogin', 'uses' => 'ApiController@login'));
Route::group(array('before' => 'apiauth'), function()
{
    //Route::controller('api/v1', 'ApiController');
	Route::any('api/v1/logout', array('uses' => 'ApiController@anyLogout', 'as' => 'anyLogout'));
	Route::group(array('before' => 'makeSureModelExists'), function()
	{
		Route::get('api/v1/{modelName}', array('uses' => 'ApiController@getAll', 'as' => 'getAll'));
		Route::get('api/v1/{modelName}/{id}', array('uses' => 'ApiController@getSpecific', 'as' => 'getSpecific'));
		Route::post('api/v1/{modelName}', array('uses' => 'ApiController@createOne', 'as' => 'createOne'));
		Route::put('api/v1/{modelName}/{id}', array('uses' => 'ApiController@editOne', 'as' => 'editOne'));
		Route::delete('api/v1/{modelName}/{id}', array('uses' => 'ApiController@deleteOne', 'as' => 'deleteOne'));
	});
});