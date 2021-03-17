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
	public function index(Request $request)
	{
		$data = BITBuilder::on(Media::class)
						->addFilters([
							Filterable::exact("id", "media_id")
						])
						->addSorts([
							Sortable::field('id', 'media_id'),
						])
						->defaultSort("media_id")
						->removeLimit()
						->get();

		return $this->response
					->collection($data, new MediaTransformer, ["key" => "data"]);

	}
}