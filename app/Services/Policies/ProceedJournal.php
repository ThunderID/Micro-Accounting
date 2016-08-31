<?php

namespace App\Services\Policies;

use App\Entities\Journal;
use App\Entities\JournalDetail;

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

		$this->storejournaldetails($stored_journal, $journal['details']);
	}

	public function storejournaldetails(Journal $journal, array $details)
	{
		foreach ($details as $key => $value) 
		{
			$journal_detail		= JournalDetail::notid($value['id'])->journalid($journal['id'])->accountid($value['account_id'])->first();

			if($journal_detail)
			{
				$journal_detail->fill($value);
			}
			else
			{
				$journal_detail	= new JournalDetail;
				$journal_detail->fill($value);
			}

			$journal_detail->journal_id		= $journal['id'];

			if(!$journal_detail->save())
			{
				$this->errors->add('Journal', $journal_detail->getError());
			}
		}
	}

	public function deletejournal(Journal $journal)
	{
		$this->deletejournaldetails($journal);
		
		if(!$journal->delete())
		{
			$this->errors->add('Journal', $journal->getError());
		}
	}
	
	public function deletejournaldetails(Journal $journal)
	{
		foreach ($journal->details as $key => $value) 
		{
			if(!$value->delete())
			{
				$this->errors->add('Journal', $value->getError());
			}
		}
	}
}
