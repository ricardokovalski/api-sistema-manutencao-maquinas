<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('machine_id');
            $table->unsignedInteger('review_type_id');

            $table->string('description', 255);
            $table->date('review_at');

            $table->timestamps();
        });

        Schema::table('maintenance', function(Blueprint $table) {
            $table->foreign('machine_id')
                ->references('id')
                ->on('machines');

            $table->foreign('review_type_id')
                ->references('id')
                ->on('review_types');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenance');
    }
}
