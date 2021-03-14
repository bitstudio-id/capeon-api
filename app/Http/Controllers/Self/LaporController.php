<?php

namespace App\Http\Controllers\Self;

use App\Exceptions\BadRequestException;
use App\Exceptions\ForbiddenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LaporStoreRequest;
use App\Http\Transformers\Self\LaporTransformer;
use App\Models\Self\Lapor;
use App\Models\Self\LaporBank;
use App\Models\Self\LaporFoto;
use App\Models\Self\LaporMedia;
use App\Models\Self\LaporThumbnail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class LaporController extends Controller {
	public function index(Request $request)
	{
		$per_page = 20;

		if($request->filled("per_page")) {
			$per_page = $request->per_page;
		}

		$data = Lapor::query()->with("foto", "media", "bank", "bank.bank")
							->orderBy("lapor_id", "desc")
							->where("lapor_status", "publish");

		if($per_page < 0) {
			$data = $data->get();
			return $this->response->collection($data, new LaporTransformer, ["key" => "data"]);
		} else {
			if($per_page > 100) {
				throw new BadRequestException("max_value_per_page_is_100");
			}

			$data = $data->paginate($per_page);
			return $this->response->paginator($data, new LaporTransformer, ["key" => "data"]);
		}

	}

	public function self(Request $request)
	{
		$per_page = 20;

		if($request->filled("per_page")) {
			$per_page = $request->per_page;
		}

		$data = Lapor::query()->with("foto", "media", "bank", "bank.bank")
							->orderBy("lapor_id", "desc")
							->where("lapor_created_by", auth()->id());

		if($per_page < 0) {
			$data = $data->get();
			return $this->response->collection($data, new LaporTransformer, ["key" => "data"]);
		} else {
			if($per_page > 100) {
				throw new BadRequestException("max_value_per_page_is_100");
			}

			$data = $data->paginate($per_page);
			return $this->response->paginator($data, new LaporTransformer, ["key" => "data"]);
		}

	}

	public function store(LaporStoreRequest $request)
	{
		// $error = new MessageBag();

		// $error->add('error_1', 'message_1_1');
		// $error->add('error_1', 'message_1_2');
		// $error->add('error_2', 'message_2_1');

		// throw new BadRequestException("error_processing_request", $error);
		

		DB::beginTransaction();

		try {
			$date_tmp = date("Y-m-d");
			$date = date("Y-m-d H:i:s");

			$generate_no = "LAP/";
            $query_generate_on_date = "select count(*) as q from `t_lapor` where date_format(lapor_created_at, '%Y-%m-%d') = '".$date_tmp."'";

            $count_generate_on_date = DB::select($query_generate_on_date);
            $val_generate_on_data   = $count_generate_on_date[0]->q + 1;
            $date_replace           = str_replace("-", "/",$date_tmp);
            $generate_no            .= $date_replace."/";
            $uniq_generate_on_data  = "00000" . $val_generate_on_data;
            $uniq_generate_on_data  = substr($uniq_generate_on_data, -4);
            $generate_no            .= $uniq_generate_on_data;

			$lapor = new Lapor();

			$date = date("Y-m-d H:i:s");

			$lapor->lapor_nomor = $generate_no;
			$lapor->lapor_kronologi = trim($request->kronologi);
			$lapor->lapor_user_id = auth()->id();
			$lapor->lapor_created_at = $date;
			$lapor->lapor_created_by = auth()->id();
			$lapor->lapor_status = "baru";

			$lapor->save();

			if($request->filled("media")) {
				foreach ($request->media as $key => $value) {
					$lapor_media = new LaporMedia();

					$lapor_media->lapor_media_lapor_id = $lapor->lapor_id;
					$lapor_media->lapor_media_media_id = $value["media_id"];
					$lapor_media->lapor_media_nama = $value["nama"];
					$lapor_media->lapor_media_info = $value["info"];
					$lapor_media->lapor_media_created_at = $date;
					$lapor_media->lapor_media_created_by = auth()->id();
					$lapor_media->save();
				}
			}

			if($request->filled("bank")) {
				foreach ($request->bank as $key => $value) {
					$lapor_bank = new LaporBank();
					$lapor_bank->lapor_bank_lapor_id = $lapor->lapor_id;
					$lapor_bank->lapor_bank_bank_id = $value["bank_id"];
					$lapor_bank->lapor_bank_nomor_rekening = $value["nomor_rekening"];
					$lapor_bank->lapor_bank_nama_rekening = $value["nama_rekening"];
					$lapor_bank->lapor_bank_created_at = $date;
					$lapor_bank->lapor_bank_created_by = auth()->id();
					$lapor_bank->save();
				}
			}

			$now = date("Ymdhis");

			foreach ($_FILES['foto']["tmp_name"] as $key => $value) {


				$temp   = $value;
		        $name   = $_FILES['foto']['name'][$key];
		        $size   = $_FILES['foto']['size'][$key];
		        $type   = $_FILES['foto']['type'][$key];

				$explode_name = explode('.', $name);
		        
		        $extension = $explode_name[count($explode_name) - 1];
		        
		        $file_name = $now."-".Str::random(32).".".$extension;

		        $dir = public_path("images/lapor");
				

		        move_uploaded_file($temp, $dir."/".$file_name);

				// dd($temp, $dir."/".$file_name);
				$lapor_foto = new LaporFoto();
				$lapor_foto->lapor_foto_lapor_id = $lapor->lapor_id;
				$lapor_foto->lapor_foto_original = "images/lapor/".$file_name;
				$lapor_foto->lapor_foto_nama_file = $value;
				$lapor_foto->lapor_foto_created_at = $date;
				$lapor_foto->lapor_foto_created_by = auth()->id();
				
				// resize
				$imgResize = Image::make(public_path($lapor_foto->lapor_foto_original));

				if($imgResize->width() > $imgResize->height()) {
					if($imgResize->width() > 1280) {
						$imgResize->resize(1280, null, function ($constraint) {
						    $constraint->aspectRatio();
						});
					}
				}

				if($imgResize->height() > $imgResize->width()) {
					if($imgResize->height() > 1280) {
						$imgResize->resize(null, 1280, function ($constraint) {
						    $constraint->aspectRatio();
						});
					}
				}
				
				$imgResize->save(public_path($lapor_foto->lapor_foto_original), 75);

				// generate thumbnail
				$thumbSquare = Image::make(public_path($lapor_foto->lapor_foto_original))->fit(500, 500);
				
				$lapor_foto->lapor_foto_square = '/images/lapor/thumbnail/square/' . $file_name;
			    
			    $thumbSquarePath = public_path($lapor_foto->lapor_foto_square);
			    $thumbSquareImage = Image::make($thumbSquare)->save($thumbSquarePath, 75);

			    $thumbLandscape = Image::make(public_path($lapor_foto->lapor_foto_original))->fit(500, 375);
				
				$lapor_foto->lapor_foto_landscape = '/images/lapor/thumbnail/landscape/' . $file_name;
			    
			    $thumbLandscapePath = public_path($lapor_foto->lapor_foto_landscape);
			    $thumbLandscapeImage = Image::make($thumbLandscape)->save($thumbLandscapePath, 75);

				$lapor_foto->save();
			}


			DB::commit();
			$data = [
	            "meta" => [
	            	"message"   => "laporan_berhasil_dikirim",
	            ],
	            "data"      => null
	        ];

	        return response()->json($data, 200);
		} catch(\Exception $e) {
			DB::rollback();
			throw new BadRequestException($e->getMessage());
		}
	}

	public function delete($id)
	{
		$data = Lapor::where("lapor_id", $id)
						->lockForUpdate()
						->first();

		if($data->lapor_created_by == auth()->id()){
			throw new ForbiddenException("forbidden");
			
		}

		$data->lapor_deleted_at = date("Y-m-d H:i:s");
		$data->lapor_deleted_by = auth()->id();

		$data->save();

		$data = [
            "meta" => [
            	"message"   => "laporan_berhasil_dihapus",
            ],
            "data"      => null
        ];

        return response()->json($data, 200);
	}
}