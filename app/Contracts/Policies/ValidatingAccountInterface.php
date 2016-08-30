<?php

namespace App\Contracts\Policies;

interface ValidatingAccountInterface
{
	public function validateaccount(array $account);
}
