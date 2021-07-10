<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use App\Http\Transformers\Self\MediaTransformer;
use App\Models\Self\Media;
use Cacing69\BITBuilder\BITBuilder;
use Cacing69\BITBuilder\Filterable;
use Cacing69\BITBuilder\Sortable;
use Illuminate\Http\Request;

class MediaController extends Controller {
	public function all(Request $request)
	{
		$data = BITBuilder::on(Media::class)
						// ->addFilters([
						// 	Filterable::exact("id", "media_id")
						// ])
						// ->addSorts([
						// 	Sortable::field('id', 'media_id'),
						// ])
						->defaultSort("media_id")
						->removeLimit()
						->get();

		return $this->response
					->collection($data, new MediaTransformer, ["key" => "data"]);

	}

	public function index(Request $request)
	{
		// dd($request->all());
		$get_data = BITBuilder::on(Media::class)
						->addFilters([
							Filterable::exact("id", "media_id"),
							// Filterable::callback("nama", function($query, $value) {
							// 	$query->where('media_nama', "like", "%".$value."%");
							// })
							// Filterable::callback("nama", function($query, $value) {
							// 	$query->where('media_nama', "=", $value);
							// })
						])
						->addSorts([
							Sortable::field('id', 'media_id'),
						])
						->defaultSort("media_id");

		if($request->filled("filter.nama")) {
			$get_data = $get_data->where("media_nama", "like", "%".$request->filter["nama"]."%");
			// $get_data = $get_data->where("media_id", "<", $request->last_id);
		}

		// if($request->filled("limit")) {
		// 	if(!filter_var($request->limit, FILTER_VALIDATE_BOOLEAN)) {
		// 		$get_data = $get_data->removeLimit();
		// 	}
		// }


		if($request->filled("last_id")) {
			$get_data = $get_data->moveCursor("media_id", $request->last_id);
			// $get_data = $get_data->where("media_id", "<", $request->last_id);
		}

		// dd(get_sql($get_data));

		$get_data = $get_data->get();

		$meta = [
			"last_id" => get_last_id($get_data, "media_id")
		];

		return $this->response
					->collection($get_data, new MediaTransformer, ["key" => "data"])
					->setMeta($meta);

	}
}