<?php namespace Thirdsteplabs\Apibase;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Routing\Controllers\Controller;

class HomeController extends \BaseController {
	public function loginform()
	{
		return View::make('apibase::login');
	}

	public function login()
	{
		$user = array(
			'email' => Input::get('username'),
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
		return View::make('apibase::auth.forgot');
	}

	public function remindpasswordform()
	{
		return View::make('apibase::auth.remind');
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
		return View::make('apibase::auth.reset')->with('token', $token);
	}

	public function resetpassword()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = $password;
			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
			  return Redirect::back()->with('error', Lang::get($response));

			case Password::PASSWORD_RESET:
			  return Redirect::to('/');
		}
	}
}
