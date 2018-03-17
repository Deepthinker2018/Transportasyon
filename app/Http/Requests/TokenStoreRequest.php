<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class TokenStoreRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'username' => 'required',
			'password' => 'required'
		];
	}
}
