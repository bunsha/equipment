<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('equipment_id');
            $table->unsignedInteger('lead_id');
            $table->timestamp('placed_at')->nullable();
            $table->timestamp('removed_at')->nullable();
            $table->timestampsTz();

            $table->index('lead_id');
        });

        Schema::table('equipment_history', function($table) {
            $table->foreign('equipment_id')
                ->references('id')
                ->on('equipment')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_types');
    }
}
