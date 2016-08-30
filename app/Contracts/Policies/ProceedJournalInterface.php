<?php

namespace App\Contracts\Policies;

use App\Entities\Journal;

interface ProceedJournalInterface
{
	public function storejournal(array $journal);
}
