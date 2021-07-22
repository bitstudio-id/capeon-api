<?php
namespace App\Repositories\Media;

use Dingo\Api\Http\FormRequest;
use Illuminate\Support\Str;

class MediaStoreRequest extends FormRequest
{
	public function authorize()
    {
        // dd($this->user());
    	// return $this->user()->can('craete-role');
        return true;
    }

    public function rules()
    {
        return [
            'nama' => [
	      		'required'
	      	],
            'logo' => [
                "image",
                "mimes:jpg,png,jpeg,bmp,jfif"
            ],
            'nama_pendek' => [
                'required'
            ],
            'url' => [
                'required',
                'regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/'
            ],
	    ];
    }
}