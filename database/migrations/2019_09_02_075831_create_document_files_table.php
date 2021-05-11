<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_files', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('document_id')->unsigned();
            $table->foreign('document_id')->references('id')->on('documents')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->string('path_name', 100);
            $table->string('label', 50)->nullable();
            $table->string('comment', 255)->nullable();
            $table->string('mime_type', 255)->nullable();
            $table->string('language', 3)->nullable();
            $table->bigInteger('file_size');
            $table->boolean('visible_in_frontdoor')->default(1);
            $table->boolean('visible_in_oai')->default(1);
            $table->integer('sort_order');
            $table->nullableTimestamps();
        });

        Schema::create('file_hashvalues', function (Blueprint $table) {  
            $table->integer('file_id')->unsigned();
            $table->foreign('file_id')->references('id')->on('document_files')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->string('type', 50);
            $table->string('value');

            $table->primary(['file_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_hashvalues');
        Schema::dropIfExists('document_files');
    }
}
