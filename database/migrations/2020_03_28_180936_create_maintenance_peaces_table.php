<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenancePeacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_peaces', function (Blueprint $table) {
            $table->unsignedBigInteger('maintenance_id');
            $table->unsignedInteger('peace_id');
            $table->integer('amount_used');
            $table->timestamps();
        });

        Schema::table('maintenance_peaces', function(Blueprint $table) {
            $table->foreign('maintenance_id')
                ->references('id')
                ->on('maintenance');

            $table->foreign('peace_id')
                ->references('id')
                ->on('peaces');

            $table->primary([
                'maintenance_id', 'peace_id'
            ], 'maintenance_peaces_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenance_peaces');
    }
}
