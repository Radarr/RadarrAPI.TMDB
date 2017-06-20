<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIpAdressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection('mappings_mysql')->table('ip_adresses', function(Blueprint $table)
		{
			$table->foreign('mappingsid', 'FK_mappingsid_ip')->references('id')->on('mappings')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection('mappings_mysql')->table('ip_adresses', function(Blueprint $table)
		{
			$table->dropForeign('FK_mappingsid_ip');
		});
	}

}
