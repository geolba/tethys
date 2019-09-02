<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_subjects', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('document_id')->unsigned();
            $table->foreign('document_id')->references('id')->on('documents')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->string('language', 3)->nullable();
            $table->enum(
                'type',
                ['uncontrolled']
            );
            $table->string('value', 255);
            $table->string('external_key', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_subjects');
    }
}
