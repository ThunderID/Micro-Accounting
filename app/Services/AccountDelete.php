<?php

namespace App\Services;

use Illuminate\Support\MessageBag;

use App\Entities\Account;

use App\Contracts\AccountDeleteInterface;

use App\Contracts\Policies\ValidatingAccountInterface;
use App\Contracts\Policies\ProceedAccountInterface;
use App\Contracts\Policies\EffectAccountInterface;

class AccountDelete implements AccountDeleteInterface 
{
	protected $account;
	protected $errors;
	protected $saved_data;
	protected $pre;
	protected $post;
	protected $pro;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct(ValidatingAccountInterface $pre, ProceedAccountInterface $pro, EffectAccountInterface $post)
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
	public function delete(Account $account)
	{
		$this->account 			= $account->toArray();
		
		/** PREPROCESS */

		//1. Validate Account
		$this->pre->validateaccountdelete($account); 

		if($this->pre->errors->count())
		{
			$this->errors 		= $this->pre->errors;

			return false;
		}

		\DB::BeginTransaction();

		/** PROCESS */

		//2. Delete goods
		$this->pro->deleteaccount($account); 

		if($this->pro->errors->count())
		{
			\DB::rollback();
			
			$this->errors 		= $this->pro->errors;

			return false;
		}

		\DB::commit();

		//3. Return Account Model Object
		$this->saved_data		= $this->account;

		return true;
	}
}
