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
        Schema::create('gaji_mandor', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->string('level');
            $table->double('pokok')->nullable();
            $table->double('pokok_baru')->nullable();
            $table->double('lembur')->nullable();
            $table->double('lembur_baru')->nullable();
            $table->integer('mandor');
            $table->integer('kreator');
            $table->integer('approved');
            $table->integer('approver')->nullable();
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
        Schema::dropIfExists('gaji_mandor');
    }
};
