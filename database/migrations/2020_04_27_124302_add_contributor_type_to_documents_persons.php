<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Config;

class AddContributorTypeToDocumentsPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('link_documents_persons', function (Blueprint $table) {
            $table->enum('contributor_type', Config::get('enums.contributor_types'))->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('link_documents_persons', function (Blueprint $table) {
            $table->dropColumn('contributor_type');
        });
    }
}
