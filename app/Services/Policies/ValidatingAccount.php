<?php

namespace App\Services\Policies;

use App\Contracts\Policies\ValidatingAccountInterface;

use Illuminate\Support\MessageBag;

use App\Entities\Account;
use App\Entities\Journal;

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

	public function validateaccountdelete(Account $account)
	{
		$exists_journal_1	= Journal::accountid($account['id'])->first();
		$exists_journal_2	= Journal::parentaccountid($account['id'])->first();

		if(!$account->count())
		{
			$this->errors->add('Account', 'Akun tidak valid');
		}
		elseif($exists_journal_1 || $exists_journal_2)
		{
			$this->errors->add('Account', 'Tidak dapat menghapus akun yang digunakan dalam jurnal');
		}
	}
}

