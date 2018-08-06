<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('oai_subset', 255)->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
			$table->foreign('parent_id')->references('id')->on('collections')->onDelete('cascade');
            $table->boolean('visible')->default('1');
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
        Schema::drop('collections');
    }
}
