<?php

namespace App\Services\Policies;

use Illuminate\Support\MessageBag;

use App\Contracts\Policies\EffectAccountInterface;

class EffectAccount implements EffectAccountInterface
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
