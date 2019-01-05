<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128);
            $table->string('description', 2048);
            $table->tinyInteger('is_available')->nullable();
            $table->tinyInteger('is_default')->nullable();
            $table->unsignedInteger('account_id')->nullable();
            $table->timestampTz('deleted_at')->nullable();
            $table->timestampsTz();

            $table->index('account_id');
            $table->index('is_available');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_statuses');
    }
}
