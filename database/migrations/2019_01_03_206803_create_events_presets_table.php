<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned()->nullable();
            $table->integer('service_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('service', 255)->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('attached_at')->nullable();
            $table->timestamp('detached_at')->nullable();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_events');
    }
}
