<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkDocumentsCollections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link_documents_collections', function (Blueprint $table) {
            // $table->increments('id');
            
            $table->unsignedInteger('collection_id')->nullable()->index();    
            $table->foreign('collection_id')
                ->references('id')->on('collections')
                ->onDelete('set null');
                //->onDelete('cascade');

            $table->unsignedInteger('document_id')->nullable()->index();
            $table->foreign('document_id')
                ->references('id')->on('documents')
                ->onDelete('set null');
                //->onDelete('cascade');

            $table->primary(['collection_id', 'document_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('link_documents_collections');
    }
}
