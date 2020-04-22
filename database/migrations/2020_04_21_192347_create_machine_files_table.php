<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachineFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_files', function (Blueprint $table) {
            $table->unsignedBigInteger('machine_id');
            $table->unsignedBigInteger('file_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('machine_files', function(Blueprint $table) {
            $table->foreign('machine_id')
                ->references('id')
                ->on('machines');

            $table->foreign('file_id')
                ->references('id')
                ->on('files');

            $table->primary([
                'machine_id', 'file_id'
            ], 'machine_files_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machine_files');
    }
}
