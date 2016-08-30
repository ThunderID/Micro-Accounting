<?php

namespace App\Services\Policies;

use Illuminate\Support\MessageBag;

use App\Contracts\Policies\EffectTransactionInterface;

class EffectTransaction implements EffectTransactionInterface
{
	public $errors;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct()
	{
		$this->errors 		= new MessageBag;
	}
}
