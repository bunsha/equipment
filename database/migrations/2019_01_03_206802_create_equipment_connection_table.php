<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentConnectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_connection', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->string('service', 255);
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('user_id');
            $table->json('meta')->nullable();
            $table->timestamp('attached_at')->nullable();
            $table->timestamp('detached_at')->nullable();
            $table->timestampsTz();

            $table->index('service_id');
            $table->index('user_id');
        });

        Schema::table('equipment_connection', function($table) {
            $table->foreign('item_id')
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
        Schema::dropIfExists('equipment_connection');
    }
}
