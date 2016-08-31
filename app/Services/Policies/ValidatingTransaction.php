<?php

namespace App\Services\Policies;

use App\Contracts\Policies\ValidatingTransactionInterface;

use Illuminate\Support\MessageBag;

use App\Entities\Transaction;
use App\Entities\Journal;

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

	public function notinjournal(Transaction $transaction)
	{
		$exists_journal			= Journal::transactionid($transaction['id'])->companyid($transaction['company_id'])->first();

		if(!is_null($exists_journal['id']))
		{
			$this->errors->add('Transaction', 'Tidak dapat menghapus dokumen yang sudah terjournal');
		}
	}

	public function validatecashnotedelete(Transaction $transaction)
	{
		$this->notinjournal($transaction);
	}

	public function validatechequedelete(Transaction $transaction)
	{
		$this->notinjournal($transaction);
	}

	public function validatecreditmemodelete(Transaction $transaction)
	{
		$this->notinjournal($transaction);
	}

	public function validatedebitmemodelete(Transaction $transaction)
	{
		$this->notinjournal($transaction);
	}

	public function validategirodelete(Transaction $transaction)
	{
		$this->notinjournal($transaction);
	}

	public function validateinvoicedelete(Transaction $transaction)
	{
		$this->notinjournal($transaction);
	}

	public function validatememorialdelete(Transaction $transaction)
	{
		$this->notinjournal($transaction);
	}

	public function validatereceiptdelete(Transaction $transaction)
	{
		$this->notinjournal($transaction);
	}

}

