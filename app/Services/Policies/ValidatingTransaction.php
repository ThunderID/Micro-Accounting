<?php

namespace App\Services\Policies;

use App\Contracts\Policies\ValidatingTransactionInterface;

use Illuminate\Support\MessageBag;

use App\Entities\Transaction;

class ValidatingTransaction implements ValidatingTransactionInterface
{
	public $errors;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct()
	{
		$this->errors 	= new MessageBag;
	}

	public function validatecashnote(array $transaction)
	{
		$this->uniquedocnumber($transaction);
	}

	public function validatecheque(array $transaction)
	{
		$this->uniquedocnumber($transaction);
	}

	public function validatecreditmemo(array $transaction)
	{
		$this->uniquedocnumber($transaction);
	}

	public function validatedebitmemo(array $transaction)
	{
		$this->uniquedocnumber($transaction);
	}

	public function validategiro(array $transaction)
	{
		$this->uniquedocnumber($transaction);
	}

	public function validateinvoice(array $transaction)
	{
		$this->uniquedocnumber($transaction);
	}

	public function validatememorial(array $transaction)
	{
		$this->uniquedocnumber($transaction);
	}

	public function validatereceipt(array $transaction)
	{
		$this->uniquedocnumber($transaction);
	}

	public function uniquedocnumber(array $transaction)
	{
		$exists_document		= Transaction::docnumber($transaction['doc_number'])->companyid($transaction['company_id'])->notid($transaction['id'])->first();

		if(!is_null($exists_document['id']))
		{
			$this->errors->add('Transaction', 'Nomor dokumen harus unik');
		}
	}
}

