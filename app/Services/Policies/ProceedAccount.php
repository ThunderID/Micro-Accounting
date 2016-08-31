<?php

namespace App\Services\Policies;

use App\Entities\Account;

use Illuminate\Support\MessageBag;

use App\Contracts\Policies\ProceedAccountInterface;

class ProceedAccount implements ProceedAccountInterface
{
	public $errors;
	public $account;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct()
	{
		$this->errors 			= new MessageBag;
	}

	public function storeaccount(array $account)
	{
		$stored_account			= Account::id($account['id'])->first();

		if(is_null($stored_account['id']))
		{
			$stored_account		= new Account;
		}
		
		$stored_account->fill($account);

		if(!$stored_account->save())
		{
			$this->errors->add('Account', $stored_account->getError());
		}

		$this->account			= $stored_account;
	}

	public function deleteaccount(Account $account)
	{
		if(!$account->delete())
		{
			$this->errors->add('Account', $account->getError());
		}
	}
}
