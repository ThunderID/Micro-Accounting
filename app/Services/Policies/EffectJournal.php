<?php

namespace App\Services\Policies;

use Illuminate\Support\MessageBag;

use App\Contracts\Policies\EffectJournalInterface;

class EffectJournal implements EffectJournalInterface
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
