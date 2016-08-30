<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/accounts',
	[
		'uses'				=> 'AccountController@index'
	]
);

$app->post('/accounts',
	[
		'uses'				=> 'AccountController@post'
	]
);

$app->delete('/accounts',
	[
		'uses'				=> 'AccountController@delete'
	]
);

$app->get('/transactions/{type}',
	[
		'uses'				=> 'TransactionController@index'
	]
);

$app->post('/transactions/{type}',
	[
		'uses'				=> 'TransactionController@post'
	]
);

$app->delete('/transactions/{type}',
	[
		'uses'				=> 'TransactionController@delete'
	]
);

$app->get('/journals',
	[
		'uses'				=> 'JournalController@index'
	]
);

$app->post('/journals',
	[
		'uses'				=> 'JournalController@post'
	]
);

$app->delete('/journals',
	[
		'uses'				=> 'JournalController@delete'
	]
);