<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTitleMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mappings_mysql')->create('title_mappings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('aka_title', 65535);
            $table->text('aka_clean_title', 65535);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mappings_mysql')->drop('title_mappings');
    }
}
