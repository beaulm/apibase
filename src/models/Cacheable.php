<?php namespace Thirdsteplabs\Apibase;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

abstract class Cacheable extends Apiable {
	public static function getCacheableData($key = null, $options = array())
	{
    	//Return all records in JSON
    	return self::all()->toJson();
	}

	private static function getCacheKeyName($key = null, $options = array())
	{
		//Set the default cache key
		$cacheKey = $key;

		//If no key was passed in
		if(is_null($cacheKey))
		{
			$cacheKey = strtolower(get_called_class());
		}

		//If a user id was passed in the options, add it to the key name
		if(isset($options['user_id']))
		{
			$cacheKey = $cacheKey.'-'.hash(Config::get('constants.default_hash_algorithm'), json_encode($options));
		}

		return $cacheKey;
	}

	//Get the cachable hash
	public static function getCacheableHash($key = null, $options = array())
	{
		//Get the cache key
		$cacheKey = static::getCacheKeyName($key, $options);

		//If the hash isn't cached
		if(!Cache::has($cacheKey))
		{
			//Get the cachable data
			$cacheableData = static::getCacheableData($key, $options);

			if($cacheableData === false) return false;

			//Hash it
			$cacheableHash = hash(Config::get('constants.default_hash_algorithm'), $cacheableData);

			//Cache it
		    Cache::put($cacheKey, $cacheableHash, Config::get('constants.default_cache_time'));
		}

		//Get the current card hash from the cache
		$hashFromCache = Cache::get($cacheKey);

		//Return the hash
		return $hashFromCache;
	}

	//Remove the hash from the cache
	public static function clearCache($key = null, $options = array())
	{
		//Get the cache key
		$cacheKey = static::getCacheKeyName($key, $options);

		//Remove the hash from the cache
		Cache::forget($cacheKey);
	}
}