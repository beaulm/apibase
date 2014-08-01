<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
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
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

Route::filter('basic.once', function()
{
    return Auth::onceBasic();
});

Route::filter('apiauth', function()
{
    if(Input::has('token'))
    {
        $login = Login::where('token', Input::get('token'))->where('ip_address', Request::getClientIp())->first();
    }
    if(isset($login) and is_object($login))
    {
    	$login->touch();
    }
    else
    {
		return Response::json(array('code' => 401, 'message' => Lang::get('apibase::thirdstep.response_message.access_denied')), 401);
    }
});

Route::filter('makeSureModelExists', function($route)
{
	if(!class_exists(ucfirst($route->getParameter('modelName'))))
	{
		$validRoute = false;
		$routeCollection = Route::getRoutes();
		foreach($routeCollection as $route)
		{
			if(Request::path() == $route->getPath())
			{
				$validRoute = true;
				break;
			}
		}
		if(!$validRoute)
		{
			return Response::json(array('code' => 401, 'message' => Lang::get('apibase::thirdstep.response_message.invalid_request')), 401);
		}
	}
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
