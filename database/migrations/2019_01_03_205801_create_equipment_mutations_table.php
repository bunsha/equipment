<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentMutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_mutations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128);
            $table->string('display_name', 256);
            $table->string('data_type', 128);
            $table->json('value')->nullable();
            $table->tinyInteger('uses_external_value')->nullable();
            $table->json('external')->nullable();
            $table->tinyInteger('is_replaceable')->nullable();
            $table->tinyInteger('is_replacing')->nullable();
            $table->tinyInteger('is_hidden')->nullable();
            $table->tinyInteger('is_function')->nullable();
            $table->tinyInteger('is_required')->nullable();
            $table->json('dependencies')->nullable();
            $table->unsignedInteger('account_id')->nullable();
            $table->timestampTz('deleted_at')->nullable();
            $table->timestampsTz();

            $table->index('name');
            $table->index('uses_external_value');
            $table->index('account_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_mutations');
    }
}
