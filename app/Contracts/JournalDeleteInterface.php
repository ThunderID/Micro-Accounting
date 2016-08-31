<?php

namespace App\Contracts;

use App\Contracts\Policies\ValidatingJournalInterface;
use App\Contracts\Policies\ProceedJournalInterface;
use App\Contracts\Policies\EffectJournalInterface;

use App\Entities\Journal;

interface JournalDeleteInterface
{
	function __construct(ValidatingJournalInterface $pre, ProceedJournalInterface $pro, EffectJournalInterface $post);
	public function getError();
	public function getData();
	public function delete(Journal $journal);
}