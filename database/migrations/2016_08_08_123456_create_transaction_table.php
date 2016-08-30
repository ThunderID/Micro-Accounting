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
			$table->integer('assigned_to')->unsigned()->index();
			
			$table->enum('type', ['receipt', 'cash_note', 'cheque', 'invoice', 'credit_memo', 'debit_memo', 'memorial', 'giro']);
			$table->string('doc_number', 255);
			$table->string('ref_number', 255);

			$table->text('issuer_information');
			$table->text('assignee_information');
			$table->text('company_information');
			
			$table->datetime('issued_at');
			$table->datetime('transact_at');
			$table->datetime('due_at')->nullable();
			
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'doc_number', 'type']);
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
