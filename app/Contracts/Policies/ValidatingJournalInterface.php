<?php

namespace App\Contracts\Policies;

interface ValidatingJournalInterface
{
	public function validatejournal(array $journal);
}
