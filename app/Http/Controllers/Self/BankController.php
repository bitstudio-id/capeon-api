<?php

namespace App\Http\Controllers\Self;

use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Http\Transformers\Self\BankTransformer;
use App\Models\Self\Bank;
use Illuminate\Http\Request;

class BankController extends Controller {
	public function index(Request $request)
	{
		$per_page = 20;

		if($request->filled("per_page")) {
			$per_page = $request->per_page;
		}

		$data = Bank::query();

		if($per_page < 0) {
			$data = $data->get();
			return $this->response->collection($data, new BankTransformer, ["key" => "data"]);
		} else {
			if($per_page > 100) {
				throw new BadRequestException("max_value_per_page_is_100");
			}
			
			$data = $data->paginate($per_page);
			return $this->response->paginator($data, new BankTransformer, ["key" => "data"]);
		}

	}
}