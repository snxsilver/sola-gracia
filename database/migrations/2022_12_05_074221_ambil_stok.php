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
        Schema::create('ambil_stok', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('stok');
            $table->double('kuantitas');
            $table->double('harga');
            $table->integer('bukukas');
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
        Schema::dropIfExists('ambil_stok');
    }
};
