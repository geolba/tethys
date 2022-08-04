<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDepthCoverage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coverage', function (Blueprint $table) {
            $table->integer('elevation_min')->nullable()->change();
            $table->integer('elevation_max')->nullable()->change();
            $table->integer('elevation_absolut')->nullable()->change();
            $table->integer('depth_min')->nullable()->change();
            $table->integer('depth_max')->nullable()->change();
            $table->integer('depth_absolut')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coverage', function (Blueprint $table) {
            $table->smallInteger('elevation_min')->nullable()->change();
            $table->smallInteger('elevation_max')->nullable()->change();
            $table->smallInteger('elevation_absolut')->nullable()->change();
            $table->smallInteger('depth_min')->nullable()->change();
            $table->smallInteger('depth_max')->nullable()->change();
            $table->smallInteger('depth_absolut')->nullable()->change();
        });
    }
}
