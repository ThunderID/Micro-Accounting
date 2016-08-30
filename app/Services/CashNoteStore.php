<?php

namespace App\Services;

use Illuminate\Support\MessageBag;

use App\Entities\Transaction;

use App\Contracts\TransactionStoreInterface;

use App\Contracts\Policies\ValidatingTransactionInterface;
use App\Contracts\Policies\ProceedTransactionInterface;
use App\Contracts\Policies\EffectTransactionInterface;

class CashNoteStore implements TransactionStoreInterface 
{
	protected $transaction;
	protected $errors;
	protected $saved_data;
	protected $pre;
	protected $pro;
	protected $post;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct(ValidatingTransactionInterface $pre, ProceedTransactionInterface $pro, EffectTransactionInterface $post)
	{
		$this->errors 		= new MessageBag;
		$this->pre 			= $pre;
		$this->pro 			= $pro;
		$this->post 		= $post;
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
	 * Checkout
	 *
	 * 1. Call Class fill
	 * 
	 * @return Response
	 */
	public function fill(array $transaction)
	{
		$this->transaction 		= $transaction;
	}

	/**
	 * Save
	 *
	 * Here's the workflow
	 * 
	 * @return Response
	 */
	public function save()
	{
		$pre_transaction		= Transaction::id($this->transaction['id'])->first();
	
		/** PREPROCESS */

		//1. validate receipt
		$this->pre->validatecashnote($this->transaction); 

		if($this->pre->errors->count())
		{
			$this->errors 				= $this->pre->errors;

			return false;
		}

		\DB::BeginTransaction();

		/** PROCESS */

		//2. Store Data cashnote
		$this->pro->storecashnote($this->transaction); 
		
		if($this->pro->errors->count())
		{
			\DB::rollback();

			$this->errors 		= $this->pro->errors;

			return false;
		}

		\DB::commit();

		//3. Return transaction Model Object
		$pro_transaction		= Transaction::id($this->pro->transaction['id'])->with(['details'])->first();

		$this->saved_data		= $pro_transaction;

		return true;
	}
}
