<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('journals', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('company_id')->unsigned()->index();
			$table->integer('transaction_id')->unsigned()->index();
			$table->integer('parent_account_id')->unsigned()->index();
			$table->integer('account_id')->unsigned()->index();
			$table->text('description');
			$table->double('debit');
			$table->double('credit');
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'company_id', 'parent_account_id']);
			$table->index(['deleted_at', 'company_id', 'account_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('journals');
	}
}
