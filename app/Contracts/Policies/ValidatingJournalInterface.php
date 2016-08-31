<?php

namespace App\Contracts\Policies;

use App\Entities\Journal;

interface ValidatingJournalInterface
{
	public function validatejournal(array $journal);

	public function validatejournaldelete(Journal $journal);
}
