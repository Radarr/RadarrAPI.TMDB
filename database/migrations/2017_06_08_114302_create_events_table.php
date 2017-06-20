<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('mappings_mysql')->create('events', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type')->unsigned();
			$table->integer('mappings_id')->unsigned()->index('FK_mappings_events');
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('mappings_mysql')->drop('events');
	}

}
