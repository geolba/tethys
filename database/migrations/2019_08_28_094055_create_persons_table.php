<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');

            $table->string('academic_title', 255)->nullable();
            $table->string('date_of_birth', 100)->nullable();
            $table->string('email', 100);
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->string('place_of_birth', 255)->nullable();
            $table->string('identifier_orcid', 50)->nullable();
            $table->string('identifier_gnd', 50)->nullable();
            $table->string('identifier_misc', 50)->nullable();
            $table->boolean('status')->nullable()->default(1);
            $table->integer('registered_at')->nullable();
            $table->string('name_type', 50)->nullable();
        });

        Schema::create('link_documents_persons', function (Blueprint $table) {
            // $table->increments('id');

            $table->unsignedInteger('person_id')->index('ix_fk_link_documents_persons_persons');
            $table->foreign('person_id', 'fk_link_documents_persons_persons')
                ->references('id')->on('persons')
                ->onDelete('no action')->onUpdate('no action'); //detach the relation via code

            $table->unsignedInteger('document_id')->index('ix_fk_link_persons_documents_documents');
            $table->foreign('document_id', 'fk_link_persons_documents_documents')
                ->references('id')->on('documents')
                ->onDelete('cascade')->onUpdate('cascade');

            $table
                ->enum('role', ['author', 'contributor', 'other'])
                ->default('other');
            $table->tinyInteger('sort_order');
            $table->boolean('allow_email_contact')->default(0);
            $table->primary(['person_id', 'document_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_documents_persons');
        Schema::dropIfExists('persons');
       
    }
}
