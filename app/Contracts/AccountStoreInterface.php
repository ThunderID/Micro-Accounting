<?php

namespace App\Contracts;

use App\Contracts\Policies\ValidatingAccountInterface;
use App\Contracts\Policies\ProceedAccountInterface;
use App\Contracts\Policies\EffectAccountInterface;

interface AccountStoreInterface
{
	function __construct(ValidatingAccountInterface $pre, ProceedAccountInterface $pro, EffectAccountInterface $post);
	public function getError();
	public function getData();
	public function fill(array $account);
	public function save();
}