<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Table for identifiers referencing to related datasets.
        Schema::create('document_references', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('document_id')->unsigned();
            $table->foreign('document_id')->references('id')->on('documents')
            ->onUpdate('cascade')->onDelete('cascade');
          
            $table->enum(
                'type',
                ["doi", "handle", "isbn", "issn",  "url", "urn"]
            );
            $table->enum(
                'relation',
                ["IsSupplementTo", "IsSupplementedBy", "IsContinuedBy", "Continues",
                "IsNewVersionOf", "IsPartOf", "HasPart", "Compiles", "IsVariantFormOf"]
            );
            $table->string('value', 255);
            $table->string('label', 50);
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
        Schema::dropIfExists('document_references');
    }
}
