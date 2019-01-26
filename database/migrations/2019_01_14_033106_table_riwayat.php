<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableRiwayat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('berobat_id');
            $table->foreign('berobat_id')->references('id')->on('berobats');
            $table->string('anamnese');
            $table->string('diagnosa');
            $table->string('terapi');
            $table->string('dokter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayats');
    }
}
