<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatasetAbstractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dataset_abstracts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('document_id')->unsigned();
            $table->foreign('document_id')->references('id')->on('documents')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->enum(
                'type',
                ['abstract' => 'Abstract', 'methods' => 'Methods', 'series_information' => 'Series_information', 'technical_info' => 'Technical_info', 'translated' => 'Translated', 'other' => 'Other']
            );
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
        Schema::dropIfExists('dataset_abstracts');
    }
}
