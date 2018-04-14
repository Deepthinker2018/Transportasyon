<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Models\User;

class UserController extends Controller
{
	public function __construct()
	{
		$this->model = User::class;
	}

	public function me()
	{
		$user = request()->user();
		return $this->item($user);
	}
}
