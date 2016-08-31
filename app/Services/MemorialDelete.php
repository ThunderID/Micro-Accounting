<?php

namespace App\Services;

use Illuminate\Support\MessageBag;

use App\Entities\Transaction;

use App\Contracts\TransactionDeleteInterface;

use App\Contracts\Policies\ValidatingTransactionInterface;
use App\Contracts\Policies\ProceedTransactionInterface;
use App\Contracts\Policies\EffectTransactionInterface;

class MemorialDelete implements TransactionDeleteInterface 
{
	protected $transaction;
	protected $errors;
	protected $saved_data;
	protected $pre;
	protected $post;
	protected $pro;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct(ValidatingTransactionInterface $pre, ProceedTransactionInterface $pro, EffectTransactionInterface $post)
	{
		$this->errors 	= new MessageBag;
		$this->pre 		= $pre;
		$this->pro 		= $pro;
		$this->post 	= $post;
	}

	/**
	 * return errors
	 *
	 * @return MessageBag
	 **/
	function getError()
	{
		return $this->errors;
	}

	/**
	 * return saved_data
	 *
	 * @return saved_data
	 **/
	function getData()
	{
		return $this->saved_data;
	}

	/**
	 * Save
	 *
	 * Here's the workflow
	 * 
	 * @return Response
	 */
	public function delete(Transaction $transaction)
	{
		$this->transaction 			= $transaction->toArray();
		
		/** PREPROCESS */

		//1. Validate Transaction
		$this->pre->validatememorialdelete($transaction); 

		if($this->pre->errors->count())
		{
			$this->errors 		= $this->pre->errors;

			return false;
		}

		\DB::BeginTransaction();

		/** PROCESS */

		//2. Delete cash note
		$this->pro->deletememorial($transaction); 

		if($this->pro->errors->count())
		{
			\DB::rollback();
			
			$this->errors 		= $this->pro->errors;

			return false;
		}

		\DB::commit();

		//3. Return Transaction Model Object
		$this->saved_data		= $this->transaction;

		return true;
	}
}
