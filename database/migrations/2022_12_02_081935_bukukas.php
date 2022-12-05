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
        Schema::create('bukukas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->mediumText('keterangan');
            $table->double('keluar')->nullable();
            $table->double('masuk')->nullable();
            $table->string('no_bukti')->nullable();
            $table->string('nota')->nullable();
            $table->integer('kategori');
            $table->integer('proyek');
            $table->integer('kreator');
            $table->integer('ambil_stok')->nullable();
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
        Schema::dropIfExists('bukukas');
    }
};
