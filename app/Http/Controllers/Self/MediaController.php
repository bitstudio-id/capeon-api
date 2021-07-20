<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use App\Http\Transformers\Self\MediaTransformer;
// use Illuminate\Database\Eloquent\Builder;
use App\Models\Self\Media;
use Cacing69\BITBuilder\BITBuilder;
use Cacing69\BITBuilder\Filterable;
use Cacing69\BITBuilder\Sortable;
use Illuminate\Http\Request;

class MediaController extends Controller {
	public function index(Request $request)
	{
		$get_data = BITBuilder::on(Media::class)
						->addFilters([
							Filterable::exact("id", "media_id"),
							Filterable::like("nama", "media_nama", Filterable::LIKE_BEGIN)
						])
						->addSorts([
							Sortable::field('id', 'media_id'),
						])
						->defaultSort("media_id");


		if($request->filled("last_id")) {
			$get_data = $get_data->moveCursor("media_id", $request->last_id);
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