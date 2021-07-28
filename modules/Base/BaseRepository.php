<?php 
namespace Modules\Base;

use App\Exceptions\NotFilledException;

class BaseRepository {
	public function isFilled(array $params, array $keys)
	{
		foreach ($keys as $key => $value) {
			if(!array_key_exists($value, $params)) {
				throw new NotFilledException("empty ".$value." params on repository");
			}
		}
	}
}	