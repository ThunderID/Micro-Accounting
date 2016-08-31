<?php

namespace App\Contracts;

use App\Contracts\Policies\ValidatingTransactionInterface;
use App\Contracts\Policies\ProceedTransactionInterface;
use App\Contracts\Policies\EffectTransactionInterface;

use App\Entities\Transaction;

interface TransactionDeleteInterface
{
	function __construct(ValidatingTransactionInterface $pre, ProceedTransactionInterface $pro, EffectTransactionInterface $post);
	public function getError();
	public function getData();
	public function delete(Transaction $transaction);
}