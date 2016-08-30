<?php

namespace App\Contracts\Policies;

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
}
