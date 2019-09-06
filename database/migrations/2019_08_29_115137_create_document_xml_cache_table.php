<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentXmlCacheTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_xml_cache', function (Blueprint $table) {
            $table->integer('document_id')->primary();
            $table->integer('xml_version');
            $table->string('server_date_modified', 50)->nullable();
            $table->text('xml_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_xml_cache');
    }
}
