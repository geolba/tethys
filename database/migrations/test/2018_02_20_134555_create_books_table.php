<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {    
            $table->increments('id');
			$table->string('title');
			$table->string('author');
			$table->integer('year');
			$table->integer('stock');
			$table->integer('category_id')->unsigned();
			$table->foreign('category_id')->references('id')->on('categories');
			//$table->integer('shelf_id')->unsigned();	
			//$table->foreign('shelf_id');//->references('id')->on('shelves');		
			$table->integer('year_id')->default('0');
            $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('books');
    }
}
