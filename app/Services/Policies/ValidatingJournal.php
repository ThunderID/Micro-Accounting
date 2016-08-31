<?php

namespace App\Services\Policies;

use App\Contracts\Policies\ValidatingJournalInterface;

use Illuminate\Support\MessageBag;

use App\Entities\Account;
use App\Entities\Journal;

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
		//1. check account is exists
		if(isset($journal['account_id']) && ($journal['account_id']!=0 || !is_null($journal['account_id'])))
		{
			$account	= Account::id($journal['account_id'])->first();

			if(!$account)
			{
				$this->errors->add('Journal', 'Akun tidak valid');
			}
		}

		//2. check parent account is exists
		if(isset($journal['parent_account_id']) && ($journal['parent_account_id']!=0 || !is_null($journal['parent_account_id'])))
		{
			$account	= Account::id($journal['parent_account_id'])->first();

			if(!$account)
			{
				$this->errors->add('Journal', 'Akun parent tidak valid');
			}
		}
	}

	public function validatejournaldelete(Journal $journal)
	{
		//
	}
}

