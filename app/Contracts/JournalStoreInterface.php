<?php

namespace App\Contracts;

use App\Contracts\Policies\ValidatingJournalInterface;
use App\Contracts\Policies\ProceedJournalInterface;
use App\Contracts\Policies\EffectJournalInterface;

interface JournalStoreInterface
{
	function __construct(ValidatingJournalInterface $pre, ProceedJournalInterface $pro, EffectJournalInterface $post);
	public function getError();
	public function getData();
	public function fill(array $journal);
	public function save();
}