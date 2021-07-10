<?php 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\HashRequest;
use App\Models\Hash;
use Illuminate\Support\Str;

class GeneralController extends Controller {
	public function hash(HashRequest $request)
	{
		$hash = new Hash();

		$hash->hash_value = md5( time()."|".Str::random(128));
		$hash->hash_created_by = auth()->id();
		$hash->hash_created_at = date("Y-m-d H:i:s");

		$hash->hash_valid = 1;

    	// $hash->hash_used_at = date("Y-m-d H:i:s");
    	
    	$url = $request->url;

    	if(substr($url, 0, 1 ) != "/") {
    		$url = "/".$url;
    	}

		$hash->hash_url = $url;

		$hash->save();

		$data = [
			"meta" => [
				"message"   => "hash_generated",
			],
			"data"      => [
				"hash" => $hash->hash_value
			]
		];

		return response()->json($data, 200);
	}
}
?>