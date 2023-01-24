<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gaji_mandor_tukang', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            // $table->string('nama');
            $table->integer('mandor_tukang');
            $table->integer('proyek');
            $table->integer('gaji_mandor');
            $table->double('uang_pokok');
            $table->integer('jam_lembur')->nullable();
            $table->double('uang_lembur');
            $table->integer('makan');
            $table->double('uang_makan');
            $table->integer('transport');
            $table->double('uang_transport');
            $table->double('total');
            $table->integer('bukukas')->nullable();
            $table->integer('kreator');
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
        Schema::dropIfExists('gaji_mandor_tukang');
    }
};
