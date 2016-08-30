<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class JournalServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind( 'App\Contracts\Policies\ValidatingJournalInterface', 'App\Services\Policies\ValidatingJournal' );
		$this->app->bind( 'App\Contracts\Policies\ProceedJournalInterface', 'App\Services\Policies\ProceedJournal' );
		$this->app->bind( 'App\Contracts\Policies\EffectJournalInterface', 'App\Services\Policies\EffectJournal' );
	}
}
