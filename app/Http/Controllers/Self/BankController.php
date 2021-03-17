<?php

namespace App\Http\Controllers\Self;

use App\Exceptions\BadRequestException;
use App\Http\Controllers\Controller;
use App\Http\Transformers\Self\BankTransformer;
use App\Models\Self\Bank;
use Cacing69\BITBuilder\BITBuilder;
use Cacing69\BITBuilder\Filterable;
use Cacing69\BITBuilder\Sortable;
use Illuminate\Http\Request;

class BankController extends Controller {
	public function index(Request $request)
	{
		$data = BITBuilder::on(Bank::class)
						->addFilters([
							Filterable::exact("id", "bank_id")
						])
						->addSorts([
							Sortable::field('id', 'bank_id'),
						])
						->defaultSort("bank_id")
						->removeLimit()
						->get();

		return $this->response
					->collection($data, new BankTransformer, ["key" => "data"]);

	}
}