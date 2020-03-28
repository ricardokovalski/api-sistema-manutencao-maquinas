<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePeacesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('peaces', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('description', 255);
            $table->integer('stock_quantity');
            $table->integer('minimal_quantity');
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
		Schema::dropIfExists('peaces');
	}
}
