<?php 
namespace App\Http\Transformers\Self;

use League\Fractal\TransformerAbstract;

class BankTransformer extends TransformerAbstract
{
	public function transform($data)
    {

    	$transform = [
            'id' => $data->bank_id,
            'nama' => $data->bank_nama,
            'nama_pendek' => $data->bank_nama_pendek,
            "logo" => url($data->bank_logo),
        ];

        return $transform;
    }
}