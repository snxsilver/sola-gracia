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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->integer('old_id')->nullable();
            $table->string('no_invoice');
            $table->string('faktur_pajak')->nullable();
            $table->date('tanggal');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->string('nama_perusahaan');
            $table->text('alamat')->nullable();
            $table->string('telp')->nullable();
            $table->string('npwp')->nullable();
            $table->double('dp')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('total');
            $table->date('tanggal_posted')->nullable();
            $table->text('keterangan');
            $table->integer('proyek');
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
        Schema::dropIfExists('invoice');
    }
};
