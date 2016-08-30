<?php

namespace App\Services\Policies;

use App\Contracts\Policies\ValidatingJournalInterface;

use Illuminate\Support\MessageBag;

class ValidatingJournal implements ValidatingJournalInterface
{
	public $errors;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct()
	{
		$this->errors 	= new MessageBag;
	}

	public function validatejournal(array $journal)
	{
		//
	}
}

