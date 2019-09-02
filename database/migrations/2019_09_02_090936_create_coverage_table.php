<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoverageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coverage', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('dataset_id')->unsigned();
            $table->foreign('dataset_id')->references('id')->on('documents')
            ->onUpdate('cascade')->onDelete('cascade');

            $table->smallInteger('elevation_min')->nullable();
            $table->smallInteger('elevation_max')->nullable();
            $table->smallInteger('elevation_absolut')->nullable();
            $table->smallInteger('depth_min')->nullable();
            $table->smallInteger('depth_max')->nullable();
            $table->smallInteger('depth_absolut')->nullable();
            $table->dateTime('time_min')->nullable();
            $table->dateTime('time_max')->nullable();
            $table->dateTime('time_absolut')->nullable();
            $table->float('x_min')->nullable();
            $table->float('x_max')->nullable();
            $table->float('y_min')->nullable();
            $table->float('y_max')->nullable();

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
        Schema::dropIfExists('coverage');
    }
}
