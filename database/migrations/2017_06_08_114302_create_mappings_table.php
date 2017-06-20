<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMappingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('mappings_mysql')->create('mappings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tmdbid');
			$table->string('imdbid', 9)->default('');
			$table->integer('report_count')->default(1);
			$table->boolean('locked')->default(0);
			$table->integer('total_reports')->default(1);
			$table->string('mapable_type', 20)->default('');
			$table->integer('mapable_id')->unsigned();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('mappings_mysql')->drop('mappings');
	}

}
