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
			$table->enum('type', ['asset', 'liability', 'equity', 'income', 'expense']);
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'company_id']);
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
