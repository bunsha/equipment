<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->string('description', 2048)->nullable();
            $table->string('serial', 255)->nullable();
            $table->string('model', 255)->nullable();
            $table->string('bar_code', 128)->nullable();
            $table->integer('type_id')->unsigned()->nullable();
            $table->integer('account_id')->unsigned();
            $table->integer('status_id')->unsigned()->nullable();
            $table->timestampTz('purchased_at')->nullable();
            $table->timestampTz('last_service_at')->nullable();
            $table->timestampTz('next_service_at')->nullable();
            $table->timestampTz('insurance_valid_until')->nullable();
            $table->timestampTz('registration_renewal_at')->nullable();
            $table->timestampTz('deleted_at')->nullable();
            $table->timestampsTz();

            $table->index('account_id');
        });

        Schema::table('equipment', function($table) {
            $table->foreign('type_id')
                ->references('id')
                ->on('equipment_types')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('status_id')
                ->references('id')
                ->on('equipment_statuses')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}
