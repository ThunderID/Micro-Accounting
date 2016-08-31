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
$api 	= app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) 
{
    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) 
    {
		$api->get('/accounts',
			[
				'uses'				=> 'AccountController@index'
			]
		);

		$api->post('/accounts',
			[
				'uses'				=> 'AccountController@post'
			]
		);

		$api->delete('/accounts',
			[
				'uses'				=> 'AccountController@delete'
			]
		);

		$api->get('/transactions/{type}',
			[
				'uses'				=> 'TransactionController@index'
			]
		);

		$api->post('/transactions/{type}',
			[
				'uses'				=> 'TransactionController@post'
			]
		);

		$api->delete('/transactions/{type}',
			[
				'uses'				=> 'TransactionController@delete'
			]
		);

		$api->get('/journals/{type}',
			[
				'uses'				=> 'JournalController@index'
			]
		);

		$api->post('/journals/{type}',
			[
				'uses'				=> 'JournalController@post'
			]
		);

		$api->delete('/journals/{type}',
			[
				'uses'				=> 'JournalController@delete'
			]
		);

		$api->get('/reports/general/ledger/{mode}',
			[
				'uses'				=> 'ReportController@generalledger'
			]
		);
	});
});