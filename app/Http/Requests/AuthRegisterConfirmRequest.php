<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Support\Str;

class AuthRegisterConfirmRequest extends FormRequest
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
	      	'token' => [
                'required',
                'min:32',
            ],
            'kode' => [
	      		'required',
                'min:6',
	      	],
	    ];
    }
}