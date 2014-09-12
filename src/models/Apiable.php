<?php namespace Thirdsteplabs\Apibase;

use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model as Eloquent;
use LaravelBook\Ardent\Ardent;

class Apiable extends Ardent {
	//Makes sure any decimal attributes come back as floats and not strings
	public function getAttribute($key)
	{
		$value = parent::getAttribute($key);
		$allRules = self::$rules;
		if(array_key_exists($key, $allRules))
		{
			$fieldRules = explode('|', $allRules[$key]);
			if(in_array('decimal', $fieldRules) and !empty($value) and $value != '' and !is_null($value) and is_numeric($value))
			{
				return (float)$value;
			}
		}
        return $value;
    }

	public static function applyFilters($query)
	{
		if(!Input::has('filters'))
		{
			return $query;
		}

		$filters = json_decode(Input::get('filters'));

		if(!property_exists($filters, strtolower(get_called_class())))
		{
			return $query;
		}

		if(!method_exists(get_called_class(),'getApiable'))
		{
			throw new \Exception('No static getApiable() method is defined for the '.get_called_class().' class.');
		}

		foreach($filters->{strtolower(get_called_class())} as $filter)
		{
			switch($filter->method) {
			    case 'where':
			        if(in_array($filter->params[0], forward_static_call(array(get_called_class(),'getApiable'))))
			        	$query = $query->where($filter->params[0], $filter->params[1], $filter->params[2]);
			        break;

			    case 'orWhere':
			        if(in_array($filter->params[0], forward_static_call(array(get_called_class(),'getApiable'))))
			        	$query = $query->orWhere($filter->params[0], $filter->params[1], $filter->params[2]);
			        break;

			    case 'orderBy':
			        if(in_array($filter->params[0], forward_static_call(array(get_called_class(),'getApiable'))))
			        	$query = $query->orderBy($filter->params[0], $filter->params[1]);
			        break;

			    case 'skip':
			        if(is_int($filter->params[0]))
			        	$query = $query->skip($filter->params[0]);
			        break;

			    case 'take':
			        if(is_int($filter->params[0]))
			        	$query = $query->take($filter->params[0]);
			        break;
			}
		}

		return $query;
	}
}