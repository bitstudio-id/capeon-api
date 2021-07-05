<?php 
namespace App\Http\Transformers\Self;

use League\Fractal\TransformerAbstract;

class LaporTransformer extends TransformerAbstract
{
	public function transform($data)
    {

        $foto = [];
        $info = [];
        $user = null;

        // dd($data, "foto"));

        if(isset($data->foto)) {
            foreach (@$data->foto as $key => $value) {
                $foto[] = [
                    "square" => url($value->lapor_foto_square),
                    "landscape" => url($value->lapor_foto_landscape),
                    "original" => url($value->lapor_foto_original),
                ];
            }
        }

        if(isset($data->media)) {
            foreach ($data->media as $key => $value) {
                $meta = null;

                if($value->media->media_nama_pendek == "ig") {
                    $meta = [
                        "link" => $value->media->media_url."/".$value->lapor_media_nama
                    ];
                }

                $info[] = [
                    "ikon" => url($value->media->media_logo),
                    "teks" => $value->lapor_media_nama,
                    "meta" => $meta,
                ];
            }
        }

        if(isset($data->bank)) {
            foreach ($data->bank as $key => $value) {
                $info[] = [
                    "ikon" => url($value->bank->bank_logo),
                    "teks" => $value->bank->bank_nama_pendek." | ".$value->lapor_bank_nomor_rekening,
                    "meta" => null,
                ];
            }   
        }

        if(isset($data->user)) {
            $user = [
                "id" => $data->user->id,
                "nama" => $data->user->nama
            ];
        }


    	$transform = [
            'id' => $data->lapor_id,
            'nomor' => $data->lapor_nomor,
            'kronologi' => $data->lapor_kronologi,
            "status" => $data->lapor_status,
            "foto" => $foto,
            "user" => $user,
            "info" => $info,
            "tanggal" => strtotime($data->lapor_created_at),
        ];

        return $transform;
    }
}