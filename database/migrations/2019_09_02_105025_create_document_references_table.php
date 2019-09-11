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
                ["DOI", "Handle", "ISBN", "ISSN",  "URL", "URN"]
            );
            $table->enum(
                'relation',
                ["IsCitedBy", "Cites", "IsSupplementTo", "IsSupplementedBy", "IsContinuedBy", "Continues", "HasMetadata", "IsMetadataFor","IsNewVersionOf", "IsPreviousVersionOf", "IsPartOf", "HasPart", "IsReferencedBy", "References", "IsDocumentedBy", "Documents", "IsCompiledBy", "Compiles", "IsVariantFormOf", "IsOriginalFormOf", "IsIdenticalTo", "IsReviewedBy", "Reviews", "IsDerivedFrom", "IsSourceOf"]
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
