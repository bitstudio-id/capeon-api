<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use App\Http\Transformers\Self\MediaTransformer;
use App\Models\Self\Media;
use App\Repositories\Media\MediaInterface;
use App\Repositories\Media\MediaStoreRequest;
use Cacing69\BITBuilder\BITBuilder;
use Cacing69\BITBuilder\Filterable;
use Cacing69\BITBuilder\Sortable;
use Illuminate\Http\Request;

class MediaController extends Controller {
	private $media;

	public function __construct(MediaInterface $media)
	{
		$this->media = $media;
	}

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

		$get_data = $get_data->get();

		$meta = [
			"last_id" => get_last_id($get_data, "media_id")
		];

		return $this->response
					->collection($get_data, new MediaTransformer, ["key" => "data"])
					->setMeta($meta);
	}

	public function store(MediaStoreRequest $request)
	{
		$params = $request->all();

		if($_FILES["logo"]["error"] != 0) {
			$image = store_image($_FILES["logo"]);
			$params["logo"] = $image["original"];
		} else {
			$params["logo"] = "images/placeholder.jpg";
		}
		// store and resize image
		
		$params["created_by"] = auth()->id();

		$data = $this->media->create($params);

		return $this->response
					->item($data, new MediaTransformer, ["key" => "data"])
					->setMeta([
						"message" => "media_berhasil disimpan"
					]);
	}
}