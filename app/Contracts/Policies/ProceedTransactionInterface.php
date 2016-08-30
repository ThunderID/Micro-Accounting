<?php

namespace App\Contracts\Policies;

use App\Entities\Transaction;

interface ProceedTransactionInterface
{
	public function storetransaction(array $transaction);

	public function storetransactiondetails(Transaction $transaction, array $details);

	public function storecashnote(array $transaction);

	public function storecheque(array $transaction);

	public function storecreditmemo(array $transaction);

	public function storedebitmemo(array $transaction);
	
	public function storegiro(array $transaction);
	
	public function storeinvoice(array $transaction);
	
	public function storememorial(array $transaction);
	
	public function storereceipt(array $transaction);
}
