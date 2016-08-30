<?php

namespace App\Services;

use Illuminate\Support\MessageBag;

use App\Entities\Account;

use App\Contracts\AccountStoreInterface;

use App\Contracts\Policies\ValidatingAccountInterface;
use App\Contracts\Policies\ProceedAccountInterface;
use App\Contracts\Policies\EffectAccountInterface;

class AccountStore implements AccountStoreInterface 
{
	protected $account;
	protected $errors;
	protected $saved_data;
	protected $pre;
	protected $pro;
	protected $post;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct(ValidatingAccountInterface $pre, ProceedAccountInterface $pro, EffectAccountInterface $post)
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
	public function fill(array $account)
	{
		$this->account 		= $account;
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
		$pre_account			= Account::id($this->account['id'])->first();
	
		/** PREPROCESS */

		//1. validate account
		$this->pre->validateaccount($this->account); 

		if($this->pre->errors->count())
		{
			$this->errors		= $this->pre->errors;

			return false;
		}

		\DB::BeginTransaction();

		/** PROCESS */

		//2. Store Data account
		$this->pro->storeaccount($this->account); 
		
		if($this->pro->errors->count())
		{
			\DB::rollback();

			$this->errors 		= $this->pro->errors;

			return false;
		}

		\DB::commit();

		//3. Return account Model Object
		$pro_account			= Account::id($this->pro->account['id'])->first();

		$this->saved_data		= $pro_account;

		return true;
	}
}
