<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionDetailTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transaction_details', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('transaction_id')->unsigned()->index();
			$table->text('description');
			$table->integer('quantity');
			$table->double('price');
			$table->string('unit', 255);
			$table->double('discount');
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'transaction_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('transaction_details');
	}
}
