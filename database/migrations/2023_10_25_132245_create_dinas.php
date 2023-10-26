<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDinas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dinas', function (Blueprint $table) {
            $table->id('id_dinas');
            $table->integer('pegawai_id');
            $table->integer('no_sp');
            $table->string('no_surat');
            $table->date('tanggal');
            $table->date('tanggal_pulang');
            $table->string('tujuan');
            $table->string('kegiatan');
            $table->string('keterangan');
            $table->string('bulan_input');
            $table->string('transportasi');
            $table->string('jam');
            $table->string('user_id');
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
        Schema::dropIfExists('dinas');
    }
}
