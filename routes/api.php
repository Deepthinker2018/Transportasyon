<?php

$api = app(Dingo\Api\Routing\Router::class);

$api->version('v1', ['namespace' => 'App\Http\Controllers\Api\V1'], function($api) {
	$api->post('token', 'TokenController@store');

	$api->group(['middleware' => 'auth:api'], function($api) {
		$api->resource('users', 'UserController', ['only' => ['index']]);
	});
});
