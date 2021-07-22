<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Support\Str;

class LaporStoreRequest extends FormRequest
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
            'kronologi' => [
	      		'required',
                'min:100',
	      	],
            'foto' => [
                "required",
                "array",
            ],
            'foto.*' => [
                "required",
                "image",
                "mimes:jpg,png,jpeg,bmp,jfif"
            ],
            "media" => [
                "required",
                "array",
            ],
            'media.*.media_id' => [
                "required",
                'exists:m_media,media_id'
            ],
            'media.*.nama' => [
                "required"
            ],
            'media.*.info' => [
                "nullable"
            ],
            'bank.*.nama_rekening' => [
                "required"
            ],
            'bank.*.bank_id' => [
                "required",
                'exists:m_bank,bank_id'
            ],
            'bank.*.nomor_rekening' => [
                "required",
                "min:10",
            ],
	    ];
    }
}