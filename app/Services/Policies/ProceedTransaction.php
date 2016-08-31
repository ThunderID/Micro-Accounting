<?php

namespace App\Services\Policies;

use App\Entities\Transaction;
use App\Entities\TransactionDetail;

use Illuminate\Support\MessageBag;

use App\Contracts\Policies\ProceedTransactionInterface;

class ProceedTransaction implements ProceedTransactionInterface
{
	public $errors;
	public $transaction;
	public $account;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct()
	{
		$this->errors 			= new MessageBag;
	}

	public function storetransaction(array $transaction)
	{
		$stored_transaction		= Transaction::id($transaction['id'])->first();

		if(is_null($stored_transaction['id']))
		{
			$stored_transaction = new Transaction;
		}
		
		$stored_transaction->fill($transaction);

		if(!$stored_transaction->save())
		{
			$this->errors->add('Transaction', $stored_transaction->getError());
		}

		$this->transaction			= $stored_transaction;
	}

	public function storetransactiondetails(Transaction $transaction, array $details)
	{
		foreach ($details as $key => $value) 
		{
			$transaction_detail		= TransactionDetail::notid($value['id'])->transactionid($transaction['id'])->description($value['description'])->first();

			if($transaction_detail)
			{
				$transaction_detail->fill($value);
			}
			else
			{
				$transaction_detail	= new TransactionDetail;
				$transaction_detail->fill($value);
			}

			$transaction_detail->transaction_id		= $transaction['id'];

			if(!$transaction_detail->save())
			{
				$this->errors->add('Transaction', $transaction_detail->getError());
			}
		}
	}

	public function storecashnote(array $transaction)
	{
		$this->storetransaction($transaction);
		$this->storetransactiondetails($this->transaction, $transaction['details']);
	}

	public function storecheque(array $transaction)
	{
		$this->storetransaction($transaction);
		$this->storetransactiondetails($this->transaction, $transaction['details']);
	}

	public function storecreditmemo(array $transaction)
	{
		$this->storetransaction($transaction);
		$this->storetransactiondetails($this->transaction, $transaction['details']);
	}

	public function storedebitmemo(array $transaction)
	{
		$this->storetransaction($transaction);
		$this->storetransactiondetails($this->transaction, $transaction['details']);
	}

	public function storegiro(array $transaction)
	{
		$this->storetransaction($transaction);
		$this->storetransactiondetails($this->transaction, $transaction['details']);
	}

	public function storeinvoice(array $transaction)
	{
		$this->storetransaction($transaction);
		$this->storetransactiondetails($this->transaction, $transaction['details']);
	}

	public function storememorial(array $transaction)
	{
		$this->storetransaction($transaction);
		$this->storetransactiondetails($this->transaction, $transaction['details']);
	}

	public function storereceipt(array $transaction)
	{
		$this->storetransaction($transaction);
		$this->storetransactiondetails($this->transaction, $transaction['details']);
	}

	public function deletetransaction(Transaction $transaction)
	{
		if(!$transaction->delete())
		{
			$this->errors->add('Transaction', $transaction->getError());
		}
	}

	public function deletetransactiondetails(Transaction $transaction)
	{
		foreach ($transaction->details as $key => $value) 
		{
			if(!$value->delete())
			{
				$this->errors->add('Transaction', $value->getError());
			}
		}
	}

	public function deletecashnote(Transaction $transaction)
	{
		$this->deletetransactiondetails($transaction);
		$this->deletetransaction($transaction);
	}

	public function deletecheque(Transaction $transaction)
	{
		$this->deletetransactiondetails($transaction);
		$this->deletetransaction($transaction);
	}

	public function deletecreditmemo(Transaction $transaction)
	{
		$this->deletetransactiondetails($transaction);
		$this->deletetransaction($transaction);
	}

	public function deletedebitmemo(Transaction $transaction)
	{
		$this->deletetransactiondetails($transaction);
		$this->deletetransaction($transaction);
	}

	public function deletegiro(Transaction $transaction)
	{
		$this->deletetransactiondetails($transaction);
		$this->deletetransaction($transaction);
	}

	public function deleteinvoice(Transaction $transaction)
	{
		$this->deletetransactiondetails($transaction);
		$this->deletetransaction($transaction);
	}

	public function deletememorial(Transaction $transaction)
	{
		$this->deletetransactiondetails($transaction);
		$this->deletetransaction($transaction);
	}

	public function deletereceipt(Transaction $transaction)
	{
		$this->deletetransactiondetails($transaction);
		$this->deletetransaction($transaction);
	}
}
