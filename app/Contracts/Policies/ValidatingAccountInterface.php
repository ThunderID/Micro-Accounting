<?php

namespace App\Contracts\Policies;

use App\Entities\Account;

interface ValidatingAccountInterface
{
	public function validateaccount(array $account);
	
	public function validateaccountdelete(Account $account);
}
