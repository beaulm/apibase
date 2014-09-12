<?php namespace Thirdsteplabs\Apibase;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Apiable extends Eloquent {
	public static function applyFilters($query)
	{
		if(!Input::has('filters'))
		{
			return $query;
		}

		$filters = json_decode(Input::get('filters'));

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