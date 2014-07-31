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

Route::get('/', array('uses' => 'Thirdsteplabs\Apibase\Controllers\HomeController@home', 'as' => 'home'));

Route::get('login', array('uses' => 'Thirdsteplabs\Apibase\Controllers\HomeController@loginform', 'as' => 'login'));

Route::post('login', array('uses' => 'Thirdsteplabs\Apibase\Controllers\HomeController@login'));

Route::get('logout', array('uses' => 'Thirdsteplabs\Apibase\Controllers\HomeController@logout', 'as' => 'logout'));

Route::get('password/forgot', array('uses' => 'Thirdsteplabs\Apibase\Controllers\HomeController@forgotpasswordform'));

Route::get('password/remind', array('uses' => 'Thirdsteplabs\Apibase\Controllers\HomeController@remindpasswordform'));

Route::post('password/remind', array('uses' => 'Thirdsteplabs\Apibase\Controllers\HomeController@remindpassword', 'as' => 'remind'));

Route::get('password/reset/{token}', array('uses' => 'Thirdsteplabs\Apibase\Controllers\HomeController@resetpasswordform'));

Route::post('password/reset/{token}', array('uses' => 'Thirdsteplabs\Apibase\Controllers\HomeController@resetpassword', 'as' => 'reset'));

Route::any('api/v1/login', array('as' => 'apilogin', 'uses' => 'Thirdsteplabs\Apibase\Controllers\ApiController@login'));
Route::group(array('before' => 'apiauth'), function()
{
    //Route::controller('api/v1', 'ApiController');
	Route::any('api/v1/logout', array('uses' => 'Thirdsteplabs\Apibase\Controllers\ApiController@anyLogout', 'as' => 'anyLogout'));
	Route::group(array('before' => 'makeSureModelExists'), function()
	{
		Route::get('api/v1/{modelName}', array('uses' => 'Thirdsteplabs\Apibase\Controllers\ApiController@getAll', 'as' => 'getAll'));
		Route::get('api/v1/{modelName}/{id}', array('uses' => 'Thirdsteplabs\Apibase\Controllers\ApiController@getSpecific', 'as' => 'getSpecific'));
		Route::post('api/v1/{modelName}', array('uses' => 'Thirdsteplabs\Apibase\Controllers\ApiController@createOne', 'as' => 'createOne'));
		Route::put('api/v1/{modelName}/{id}', array('uses' => 'Thirdsteplabs\Apibase\Controllers\ApiController@editOne', 'as' => 'editOne'));
		Route::delete('api/v1/{modelName}/{id}', array('uses' => 'Thirdsteplabs\Apibase\Controllers\ApiController@deleteOne', 'as' => 'deleteOne'));
	});
});