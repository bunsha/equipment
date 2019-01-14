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
            $table->json('values')->nullable();
            $table->tinyInteger('is_nullable')->nullable();
            $table->tinyInteger('is_replace')->nullable();
            $table->tinyInteger('is_hidden')->nullable();
            $table->unsignedInteger('account_id')->nullable();
            $table->timestampTz('deleted_at')->nullable();
            $table->timestampsTz();

            $table->index('name');
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
