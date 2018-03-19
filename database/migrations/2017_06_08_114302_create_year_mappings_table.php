<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateYearMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mappings_mysql')->create('year_mappings', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('aka_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mappings_mysql')->drop('year_mappings');
    }
}
