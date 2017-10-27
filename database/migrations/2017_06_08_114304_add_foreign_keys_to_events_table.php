<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mappings_mysql')->table('events', function (Blueprint $table) {
            $table->foreign('mappings_id', 'FK_mappings_events')->references('id')->on('mappings')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mappings_mysql')->table('events', function (Blueprint $table) {
            $table->dropForeign('FK_mappings_events');
        });
    }
}
