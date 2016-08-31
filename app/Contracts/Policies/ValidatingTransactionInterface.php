<?php

namespace App\Contracts\Policies;

use App\Entities\Transaction;

interface ValidatingTransactionInterface
{
	public function validatecashnote(array $transaction);
	public function validatecheque(array $transaction);
	public function validatecreditmemo(array $transaction);
	public function validatedebitmemo(array $transaction);
	public function validategiro(array $transaction);
	public function validateinvoice(array $transaction);
	public function validatememorial(array $transaction);
	public function validatereceipt(array $transaction);
	
	public function uniquedocnumber(array $transaction);
	public function notinjournal(Transaction $transaction);
	
	public function validatecashnotedelete(Transaction $transaction);
	public function validatechequedelete(Transaction $transaction);
	public function validatecreditmemodelete(Transaction $transaction);
	public function validatedebitmemodelete(Transaction $transaction);
	public function validategirodelete(Transaction $transaction);
	public function validateinvoicedelete(Transaction $transaction);
	public function validatememorialdelete(Transaction $transaction);
	public function validatereceiptdelete(Transaction $transaction);
}
