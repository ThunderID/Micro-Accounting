<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('issued_by')->unsigned()->index();
			$table->integer('company_id')->unsigned()->index();
			$table->integer('issued_to')->unsigned()->index();
			$table->enum('type', ['receipt', 'cash_note', 'cheque', 'invoice', 'credit_memo', 'debet_memo', 'memorial', 'giro']);
			$table->string('ref_number', 255);
			$table->datetime('issued_at');
			$table->datetime('due_at');
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'ref_number', 'type']);
			$table->index(['deleted_at', 'issued_at', 'type']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('transactions');
	}
}
