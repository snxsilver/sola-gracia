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
        Schema::create('proyek', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->integer('old_id')->nullable();
            $table->string('kode')->nullable();
            $table->string('nama')->nullable();
            $table->double('nilai')->nullable();
            $table->double('pengeluaran')->nullable();
            $table->double('pembayaran')->nullable();
            $table->integer('pajak')->nullable();
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
        Schema::dropIfExists('proyek');
    }
};
