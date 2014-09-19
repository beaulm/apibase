<?php namespace Thirdsteplabs\Apibase;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Cacheable implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, SoftDeletingTrait;

	protected $fillable = array('username', 'name', 'phone', 'email', 'role');
	//protected $guarded = array('id', 'password');


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	protected $dates = ['deleted_at'];


	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}


	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}


	/**
	 * Hooks into the password saving functionality and makes sure it any input is hashed exactly once
	 *
	 */
	public function setPasswordAttribute($value)
	{
		if(!empty($value))
		{
			if((strlen($value) == 64 or strlen($value) == 60) and substr($value, 0, 1) == '$')
			{
				//Arf, the value is already hashed!
			}
			else
			{
				$this->attributes['password'] = Hash::make($value);
			}
		}
	}

	public function logins()
	{
		return $this->hasMany('Thirdsteplabs\Apibase\Login');
	}


	/**
	 * Checks if the current user has the queried role
	 *
	 * @return bool
	 */
	public function hasRole($role)
	{
		return !abs(strcasecmp($this->role, $role));
	}


	/**
	 *  All possible roles
	 */
	public static $roles = array('Frontend', 'Admin');


	/**
	 * Get the types of roles the current user can create
	 *
	 * @return array
	 */
	public static function getAcceptableRoles()
	{
		switch(Auth::user()->role)
		{
			case 'Admin':
				return array('Frontend', 'Admin');
				break;
			default:
				return array('Frontend', 'Admin');
				break;
		}
	}


	/**
	 * Add a token to the current user (used for merging accounts and email marketing)
	 *
	 * @return string
	 */
	public function createToken()
	{
		$this->token = hash_hmac('sha512', str_shuffle(sha1($this->email.spl_object_hash($this).microtime(true))), 'po$!rI&Trl=f*iuvi5h3a+oeS=oe2l1CIe0+a6OubR=_p2O_no@7rles IuS!334');
		$this->save();
		return $this->token;
	}


	/**
	 * Get the users token, creating one if they don't have it already
	 *
	 * @return string
	 */
	public function getToken()
	{
		if(!empty($this->token))
		{
			return $this->token;
		}
		return $this->createToken();
	}


	/**
	 *  Validation rules
	 */
	public static $rules = array(
		'username' => 'required',
		'email' => 'email',
	);

}
