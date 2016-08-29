<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index();
			$table->string('name', 255);
			$table->string('code', 255);
			$table->string('type', 255);
			$table->boolean('is_debit');
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'parent_account_id', 'transaction_id']);
			$table->index(['deleted_at', 'account_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('accounts');
	}
}
