<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatasetTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dataset_titles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->unsigned();

            $table->foreign('document_id')->references('id')->on('documents')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->text('value');
            $table->string('language', 3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dataset_titles');
    }
}
