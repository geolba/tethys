<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatasetSubjects extends Migration
{
    // php artisan make:migration create_dataset_subjects_table --table=dataset_subjects --create=dataset_subjects
// php artisan migrate

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dataset_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('language', 3)->nullable();
            $table->enum(
                'type',
                ['uncontrolled']
            );
            $table->string('value', 255);
            $table->string('external_key', 255)->nullable();
            $table->nullableTimestamps();
        });

        Schema::create('link_dataset_subjects', function (Blueprint $table) {
            // $table->increments('id');

            $table->unsignedInteger('subject_id')->index();
            $table->foreign('subject_id')
                ->references('id')->on('dataset_subjects')
                ->onDelete('no action')->onUpdate('no action'); //detach the relation via code

            $table->unsignedInteger('document_id')->index();
            $table->foreign('document_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['subject_id', 'document_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('link_dataset_subjects');
        Schema::dropIfExists('dataset_subjects');
    }
}
