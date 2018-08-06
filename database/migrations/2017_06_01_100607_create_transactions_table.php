<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('student_id')->unsigned();
			$table->foreign('student_id')->references('id')->on('students');
			$table->integer('book_id')->unsigned();
			$table->foreign('book_id')->references('id')->on('books');
			$table->integer('borrowed_at');
			$table->integer('returned_at')->default('0');
			$table->integer('fines')->default('0');
			$table->boolean('status')->default('0');
			$table->timestamps();
		});
		//  Schema::table('transactions', function($table) {
		// 	$table->foreign('student_id')->references('id')->on('students');
		// });
		//  Schema::table('transactions', function($table) {
		// 	$table->foreign('book_id')->references('id')->on('books');
		// });
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
