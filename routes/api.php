<?php

$api = app(Dingo\Api\Routing\Router::class);

$api->version('v1', function($api) {
	$api->group(['namespace' => 'App\Http\Controllers\Api\V1'], function($api) {
		$api->post('token', 'TokenController@store');
	});
});
