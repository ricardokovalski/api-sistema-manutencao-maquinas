<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachineUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_users', function (Blueprint $table) {
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::table('machine_users', function(Blueprint $table) {
            $table->foreign('machine_id')
                ->references('id')
                ->on('machines');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->primary([
                'machine_id', 'user_id'
            ], 'machine_users_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_users');
    }
}
