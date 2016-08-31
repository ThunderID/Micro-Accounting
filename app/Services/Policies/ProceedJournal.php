<?php

namespace App\Services\Policies;

use App\Entities\Journal;

use Illuminate\Support\MessageBag;

use App\Contracts\Policies\ProceedjournalInterface;

class ProceedJournal implements ProceedjournalInterface
{
	public $errors;
	public $journal;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct()
	{
		$this->errors		= new MessageBag;
	}

	public function storejournal(array $journal)
	{
		$stored_journal		= Journal::id($journal['id'])->first();

		if(is_null($stored_journal['id']))
		{
			$stored_journal = new Journal;
		}
		
		$stored_journal->fill($journal);

		if(!$stored_journal->save())
		{
			$this->errors->add('Journal', $stored_journal->getError());
		}

		$this->journal		= $stored_journal;
	}

	public function deletejournal(Journal $journal)
	{
		if(!$journal->delete())
		{
			$this->errors->add('Journal', $journal->getError());
		}
	}
}
