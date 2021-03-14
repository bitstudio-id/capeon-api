<?php 
namespace App\Http\Transformers\Self;

use League\Fractal\TransformerAbstract;

class MediaTransformer extends TransformerAbstract
{
	public function transform($data)
    {

    	$transform = [
            'id' => $data->media_id,
            'nama' => $data->media_nama,
            'nama_pendek' => $data->media_nama_pendek,
            "logo" => url($data->media_logo),
        ];

        return $transform;
    }
}