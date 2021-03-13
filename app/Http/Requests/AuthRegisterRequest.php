<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Support\Str;

class AuthRegisterRequest extends FormRequest
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
                'required',
            ],
            'username' => [
	      		'required',
	      	],
            'password' => [
                "required",
                "confirmed",
                "min:6",
            ]
	    ];
    }
}