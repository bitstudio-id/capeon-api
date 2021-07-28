<?php 
namespace Modules\Media\Repositories;	

use Modules\Base\BaseRepository;
use Modules\Media\Media;
use Modules\Media\Repositories\MediaInterface;

class MediaRepository extends BaseRepository implements MediaInterface {

	public function create($params)
	{
		$media = new Media();

		// validate on repository
		$validate = $this->isFilled($params, ["nama", "url", "logo", "nama_pendek", "created_by"]);

		$media->media_nama 			= $params["nama"];
		$media->media_url 			= $params["url"];
		$media->media_logo 			= $params["logo"];
		$media->media_nama_pendek 	= $params["nama_pendek"];
		$media->media_created_by 	= $params["created_by"];
		$media->media_created_at 	= date("Y-m-d H:i:s");

		$media->save();

		return $media;
	}
}