<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalDetailTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('journal_details', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('journal_id')->unsigned()->index();
			$table->integer('account_id')->unsigned()->index();
			$table->text('description');
			$table->double('debit');
			$table->double('credit');
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'journal_id', 'account_id']);
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
		Schema::drop('journal_details');
	}
}
