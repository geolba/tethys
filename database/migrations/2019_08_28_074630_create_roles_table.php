<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();

        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('display_name', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->nullableTimestamps();
        });

        // Create table for associating roles to accounts (Many-to-Many)
        Schema::create('link_accounts_roles', function (Blueprint $table) {
            $table->unsignedInteger('account_id')->unsigned();
            $table->unsignedInteger('role_id')->unsigned();
            
            $table->foreign('account_id')
            ->references('id')->on('accounts')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('role_id')
            ->references('id')->on('roles')
            ->onDelete('cascade')->onUpdate('cascade');
            
            $table->primary(['account_id', 'role_id']);
        });
        
        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name', 100);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('link_accounts_roles');
        Schema::dropIfExists('roles');
    }
}
