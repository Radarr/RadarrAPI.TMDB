<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIpAdressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mappings_mysql')->create('ip_adresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip', 32)->default('');
            $table->integer('mappingsid')->unsigned()->index('FK_mappingsid_ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mappings_mysql')->drop('ip_adresses');
    }
}
