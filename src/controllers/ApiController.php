<?php namespace Thirdsteplabs\Apibase;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controllers\Controller;

class ApiController extends \BaseController {
	/**
	 * Handle Login attempts
	 *
	 * @return JSON array
	 */
	public function login()
	{
		//Get the inputted username and password
		$user = array(
			'email' => Input::get('email'),
			'password' => Input::get('password')
		);

		//Check if the credentials are valid
		if(Auth::attempt($user)) 
		{
			//If so, create and return a token
			$login = new Login;
			$login->user_id = Auth::user()->id;
			$login->setIp();
			$login->createToken();
			$login->save();
			return Response::json(array('token' => $login->getToken()));
		}
		else
		{
			//If not, return an error
			return Response::json(array('code' => 401, 'message' => Lang::get('apibase::thirdstep.response_message.login_failed')), 401); 
		}
	}


	/**
	 * Handle logout requests
	 *
	 * @return JSON array
	 */
	public function anyLogout()
	{
		//Invalidate the passed token
		$user = Login::where('token', Input::get('token'))->delete();
		return Response::json(array('message' => Lang::get('apibase::thirdstep.response_message.logout_succeeded')));
	}


	/**
	 * Gets all records
	 * Use the protected $hidden attribute to specify which columns shouldn't be returned
	 *
	 * @var string
	 * @return JSON array
	 */
	public function getAll($modelName)
	{
		$results = $modelName::all();
		return Response::json($results->toArray());
	}


	/**
	 * Gets a specific record
	 * Use the protected $hidden attribute to specify which columns shouldn't be returned
	 *
	 * @var string
	 * @var integer
	 * @return JSON array
	 */
	public function getSpecific($modelName, $id)
	{
		$result = $modelName::find($id);
		if(empty($result))
		{
			return Response::json(array('code' => 401, 'message' => Lang::get('apibase::thirdstep.response_message.item_not_found')), 401); 
		}
		return Response::json($result->toArray()); 
	}


	/**
	 * Creates a record
	 *
	 * @var string
	 * @return JSON array
	 */
	public function createOne($modelName)
	{
		$result = $modelName::create(Input::all());
		return Response::json($result->toArray()); 
	}


	/**
	 * Updates a specific record
	 *
	 * @var string
	 * @var integer
	 * @return JSON array
	 */
	public function editOne($modelName, $id)
	{
		$result = $modelName::find($id);
		if(empty($result))
		{
			return Response::json(array('code' => 401, 'message' => Lang::get('apibase::thirdstep.response_message.item_not_found')), 401); 
		}
		$result->update(Input::all());
		$result->save();
		return Response::json($result->toArray()); 
	}


	/**
	 * Deletes a specific record
	 * A soft-delete may be performed
	 *
	 * @var string
	 * @var integer
	 * @return redirect
	 */
	public function deleteOne($modelName, $id)
	{
		$result = $modelName::find($id);
		if(empty($result))
		{
			return Response::json(array('code' => 401, 'message' => Lang::get('apibase::thirdstep.response_message.item_not_found')), 401); 
		}
		$result->delete();
		return Redirect::to('getAll');
	}
}