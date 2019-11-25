<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('contributing_corporation', 255)->nullable();
            $table->string('creating_corporation', 255);
            $table->string('publisher_name', 255)->nullable();
            $table->dateTime('embargo_date')->nullable();
            $table->unsignedInteger('publish_id')->unique()->nullable();
            $table->integer('project_id')->unsigned()->nullable();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->enum(
                'type',
                ['analysisdata', 'interpreteddata', 'measurementdata', 'models', 'rawdata', 'supplementarydata', 'mixedtype']
            );
            $table->string('language', 10);
            $table->enum(
                'server_state',
                Config::get('enums.server_states')
            )->default('inprogress');
            $table->boolean('belongs_to_bibliography')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('server_date_modified');
            $table->dateTime('server_date_published')->nullable();
            $table->integer('account_id')->unsigned()->nullable();
            $table->integer('editor_id')->unsigned()->nullable();
            $table->integer('reviewer_id')->unsigned()->nullable();
            $table->string('preferred_reviewer', 25)->nullable();
            $table->string('preferred_reviewer_email', 50)->nullable();
            $table->string('reject_editor_note', 500)->nullable();
            $table->string('reject_reviewer_note', 500)->nullable();
            $table->boolean('reviewer_note_visible')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
