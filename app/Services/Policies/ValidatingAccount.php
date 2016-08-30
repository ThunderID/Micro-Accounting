<?php

namespace App\Services\Policies;

use App\Contracts\Policies\ValidatingAccountInterface;

use Illuminate\Support\MessageBag;

use App\Entities\Account;

class ValidatingAccount implements ValidatingAccountInterface
{
	public $errors;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct()
	{
		$this->errors 		= new MessageBag;
	}

	public function validateaccount(array $account)
	{
		$exists_account		= Account::code($account['code'])->companyid($account['company_id'])->notid($account['id'])->first();

		if($exists_account)
		{
			$this->errors->add('Account', 'Kode akun harus unik');
		}
	}
}

