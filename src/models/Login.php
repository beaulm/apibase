<?php namespace Thirdsteplabs\Apibase;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Login extends Eloquent {

	use SoftDeletingTrait;

	protected $guarded = array('id');

	protected $dates = ['deleted_at'];

	public function user()
	{
		return $this->belongsTo('Thirdsteplabs\Apibase\User');
	}


	/**
	 * Save the users IP address
	 *
	 */
	public function setIp()
	{
		$this->ip_address = Request::getClientIp();
	}

	/**
	 * Add a token to the current session
	 *
	 */
	public function createToken()
	{
		$this->token = hash_hmac('sha512', $this->ip_address.str_shuffle(sha1(spl_object_hash($this).microtime(true))), 'po$!rI&Trl=f*iuvi5h3a+oeS=oe2l1CIe0+a6OubR=_p2O_no@7rles IuS!334');
		$this->save();
	}


	/**
	 * Get the current token
	 *
	 * @return string
	 */
	public function getToken()
	{
		if(!empty($this->token))
		{
			return $this->token;
		}
	}


	/**
	 * Check if token is valid
	 *
	 * @return string
	 */
	public static function checkToken($token, $ipAddress)
	{
		$login = Login::where('token', $token)->where('ip_address', $ipAddress)->first();
	    if(isset($login) and is_object($login))
	    {
	    	$login->touch();
	    	Auth::loginUsingId($login->user_id);
	    	return true;
	    }
	    else
	    {
	    	return false;
	    }
	}


	/**
	 *  Validation rules
	 */
	public static $rules = array(
		'user_id' => 'required|exists:users,id',
		'ip_address' => 'required|max:45',
		'token' => 'required|max:128',
	);
	
}