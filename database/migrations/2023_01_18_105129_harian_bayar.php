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
        Schema::create('harian_bayar', function (Blueprint $table) {
            $table->id();
            $table->integer('harian');
            $table->date('tanggal');
            $table->integer('proyek');
            $table->integer('bukukas')->nullable();
            $table->string('keterangan');
            $table->integer('jam')->nullable();
            $table->integer('makan');
            $table->double('uang_makan');
            $table->integer('transport');
            $table->double('uang_transport');
            $table->double('total');
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
        Schema::dropIfExists('harian_bayar');
    }
};
