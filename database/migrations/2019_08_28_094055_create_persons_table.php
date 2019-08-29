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

            $table->string('name');
            $table->integer('registered_at');
            $table->boolean('status')->default(1);

            $table->timestamps();
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
