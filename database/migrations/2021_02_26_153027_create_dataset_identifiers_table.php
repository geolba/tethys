<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatasetIdentifiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dataset_identifiers', function (Blueprint $table) {
            // primary key
            $table->increments('id');

            // foreign key to: documents.id
            $table->integer('dataset_id')->unsigned();
            ;
            $table->foreign('dataset_id')->references('id')->on('documents')
            ->onUpdate('cascade')->onDelete('cascade');

            // value of the identifier
            $table->string('value', 255);
            $table->enum(
                'type',
                ["doi", "handle", "isbn", "issn",  "url", "urn"]
            );
            // DOI registration status
            $table->enum(
                'status',
                ['draft', 'registered', 'findable']
            )->nullable();
            // timestamp of DOI registration
            $table->timestamp('registration_ts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dataset_identifiers', function (Blueprint $table) {
            //
        });
    }
}
