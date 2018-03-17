<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TokenStoreRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class TokenController extends Controller
{
	private $client;

	public function __construct()
	{
		$this->client = DB::table('oauth_clients')->where('password_client', 1)->first();
	}

	public function store()
	{
		app(TokenStoreRequest::class);

		request()->request->add([
			'username' => request()->username,
			'password' => request()->password,
			'grant_type' => 'password',
			'client_id' => $this->client->id,
			'client_secret' => $this->client->secret,
			'scope' => '*'
		]);
		return App::call('Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
	}
}
