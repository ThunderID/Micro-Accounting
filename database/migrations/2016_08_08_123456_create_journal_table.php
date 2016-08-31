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
			$table->datetime('transact_at');
			$table->enum('type', ['cash', 'accrual']);
			$table->string('currency', 255);
			$table->text('notes');
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'company_id', 'transaction_id']);
			$table->index(['deleted_at', 'company_id', 'transact_at']);
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
