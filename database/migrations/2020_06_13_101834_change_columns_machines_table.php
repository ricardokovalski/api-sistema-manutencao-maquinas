<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('machines', function(Blueprint $table) {
            $table->integer('review_period')->nullable()->change();
            $table->integer('warning_period')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('machines', function(Blueprint $table) {
            $table->integer('review_period')->change();
            $table->integer('warning_period')->change();
        });
    }
}
