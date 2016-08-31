<?php

namespace App\Contracts\Policies;

use App\Entities\Journal;

interface ProceedJournalInterface
{
	public function storejournal(array $journal);

	public function storejournaldetails(Journal $journal, array $details);
	
	public function deletejournal(Journal $journal);

	public function deletejournaldetails(Journal $journal);
}
