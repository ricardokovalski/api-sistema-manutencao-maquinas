<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachinePiecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_pieces', function (Blueprint $table) {
            $table->unsignedBigInteger('machine_id');
            $table->unsignedInteger('piece_id');
            $table->integer('minimal_quantity');
            $table->timestamps();
        });

        Schema::table('machine_pieces', function(Blueprint $table) {
            $table->foreign('machine_id')
                ->references('id')
                ->on('machines');

            $table->foreign('piece_id')
                ->references('id')
                ->on('peaces');

            $table->primary([
                'machine_id', 'piece_id'
            ], 'machine_peaces_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_pieces');
    }
}
