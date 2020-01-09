<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_licences', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(true);
            $table->string('comment_internal', 4000)->nullable();
            $table->string('desc_markup', 4000)->nullable();
            $table->string('desc_text', 4000)->nullable();
            $table->string('language', 3)->nullable();
            $table->string('link_licence', 200);
            $table->string('link_logo', 200)->nullable();
            $table->string('link_sign', 200)->nullable();
            $table->string('mime_type', 30)->nullable();
            $table->string('name_long', 200);
            $table->boolean('pod_allowed')->default(false);
            $table->tinyInteger('sort_order');
        });

        Schema::create('link_documents_licences', function (Blueprint $table) {
            // $table->increments('id');

            $table->unsignedInteger('licence_id')->index();
            $table->foreign('licence_id')
                ->references('id')->on('document_licences')
                ->onDelete('no action')->onUpdate('no action'); //detach the relation via code

            $table->unsignedInteger('document_id')->index();
            $table->foreign('document_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')->onUpdate('cascade');

            $table
                ->enum('role', ['author', 'contributor', 'other'])
                ->default('other');
            $table->primary(['licence_id', 'document_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_documents_licences');
        Schema::dropIfExists('document_licences');
    }
}
