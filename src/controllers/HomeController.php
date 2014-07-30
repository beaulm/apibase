<?php namespace Thirdsteplabs\Apibase;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\File\File as SFile;
use Illuminate\Support\Facades\Validator as LValidator;

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function home()
	{
		return View::make('hello');
	}

	public function loginform()
	{
		return View::make('login');
	}

	public function login()
	{
		$user = array(
			'email' => Input::get('email'),
			'password' => Input::get('password')
		);

		if(Auth::attempt($user)) 
		{
			if(Auth::user()->hasRole('Frontend'))
			{
				return Redirect::intended('/');
			}
			return Redirect::intended('/admin');
		}
		
		// authentication failure! lets go back to the login page
		return Redirect::route('login')
			->with('flash_error', 'Sorry, your credentials didn\'t work.')
			->withInput();
	}

	public function logout()
	{
		Auth::logout();
		return Redirect::route('home');
	}

	public function forgotpasswordform()
	{
		return View::make('auth.forgot');
	}

	public function remindpasswordform()
	{
		return View::make('auth.remind');
	}

	public function remindpassword()
	{
		$credentials = array('email' => Input::get('email'));
		return Password::remind($credentials, function($m)
		{
			$m->subject('Password Reminder');
		});
	}

	public function resetpasswordform($token)
	{
		return View::make('auth.reset')->with('token', $token);
	}

	public function resetpassword()
	{
		$credentials = array('email' => Input::get('email'));

		return Password::reset($credentials, function($user, $password)
		{
			$user->password = $password;
			$user->save();
			return Redirect::to('/');
		});
	}

}
