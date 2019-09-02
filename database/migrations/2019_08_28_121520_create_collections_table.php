<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('oai_name', 255);
            $table->integer('position');
            $table->boolean('visible')->default(1);
            $table->boolean('visible_frontdoor')->default(1);
            $table->boolean('visible_oai')->default(1);
        });

        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('role_id')->unsigned()->nullable();
            $table->foreign('role_id')
            ->references('id')->on('collections_roles')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->string('number', 255)->nullable();
            $table->string('name', 255);
            $table->string('oai_subset', 255)->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')
                ->references('id')->on('collections')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('visible')->default(1);
            $table->boolean('visible_publish')->default(1);
        });

        Schema::create('link_documents_collections', function (Blueprint $table) {
            // $table->increments('id');

            $table->unsignedInteger('collection_id')->index();
            $table->foreign('collection_id')
                ->references('id')->on('collections')
                ->onDelete('cascade')->onUpdate('cascade'); //detach the relation via code

            $table->unsignedInteger('document_id')->index();
            $table->foreign('document_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('link_documents_collections');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('collections_roles');
    }
}
