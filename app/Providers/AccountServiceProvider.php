<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind( 'App\Contracts\Policies\ValidatingAccountInterface', 'App\Services\Policies\ValidatingAccount' );
		$this->app->bind( 'App\Contracts\Policies\ProceedAccountInterface', 'App\Services\Policies\ProceedAccount' );
		$this->app->bind( 'App\Contracts\Policies\EffectAccountInterface', 'App\Services\Policies\EffectAccount' );
	}
}
