<?php namespace Thirdsteplabs\Apibase;

use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model as Eloquent;
use LaravelBook\Ardent\Ardent;

class ApiableException extends \Exception {
	public $descriptor;

	public function __construct($descriptor, $params = array()) {
		parent::__construct(\Lang::get($descriptor, $params));
		$this->descriptor = $descriptor;
	}
}

class Apiable extends Ardent {
	public static function applyFilters($query = false)
	{		
		if(!Input::has('filters'))
		{
			return $query;
		}

		$filters = json_decode(Input::get('filters'));

		$validMethods = array(
			'where' => array(
				'checkApiable' => true
			),
			'orWhere' => array(
				'checkApiable' => true
			),
			'orderBy' => array(
				'checkApiable' => true
			),
			'skip' => array(
				'checkApiable' => false
			),
			'take' => array(
				'checkApiable' => false
			)
		);
		$checkApiable = array('where', 'orWhere', 'orderBy');

		if(!$filters) {
			throw new ApiableException('apibase::thirdstep.filter_error.invalid_json');
		}

		$className = strtolower(get_called_class());

		if(!property_exists($filters, $className))
		{
			return $query;
		}

		foreach($filters->{$className} as $filter)
		{
			if(!property_exists($filter, 'method')) {
				throw new ApiableException('apibase::thirdstep.filter_error.missing_filter_method');
			}

			if(!property_exists($filter, 'params')) {
				throw new ApiableException('apibase::thirdstep.filter_error.missing_filter_params');
			}

			if(!array_key_exists($filter->method, $validMethods)) {
				throw new ApiableException('apibase::thirdstep.filter_error.unknown_method', array('method' => $filter->method));
			}

			if(!is_array($filter->params)) {
				throw new ApiableException('apibase::thirdstep.filter_error.invalid_filter_params');
			}

			if(count($filter->params) < 1) {
				throw new ApiableException('apibase::thirdstep.filter_error.too_few_filter_params');
			}
			
			if($validMethods[$filter->method]['checkApiable']) {
				if(!in_array($filter->params[0], forward_static_call(array(get_called_class(),'getApiable')))) {
					throw new ApiableException(
						'apibase::thirdstep.filter_error.unknown_filter',
						array('filter' => $filter->params[0], 'class' => $className)
					);
				 }
			}
			switch($filter->method) {
				case 'skip':
					if(!is_int($filter->params[0]))
						throw new ApiableException('apibase::thirdstep.filter_error.invalid_skip_param');
					break;

				case 'take':
					if(!is_int($filter->params[0]))
						throw new ApiableException('apibase::thirdstep.filter_error.invalid_take_param');
					break;
			}

			if($query) {
				$query = call_user_func_array(array($query, $filter->method), $filter->params);
			}
		}
		if($query) {
			return $query;
		}
		return true;
	}
}