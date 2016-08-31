<?php

namespace App\Contracts\Policies;

use App\Entities\Account;

interface ProceedAccountInterface
{
	public function storeaccount(array $account);

	public function deleteaccount(Account $account);
}
