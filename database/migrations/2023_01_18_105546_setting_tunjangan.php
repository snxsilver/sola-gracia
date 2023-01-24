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
        Schema::create('setting_tunjangan', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis',['uang_makan','transport']);
            $table->string('level');
            $table->double('nominal')->nullable();
            $table->double('nominal_baru')->nullable();
            $table->date('tanggal')->nullable();
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
        Schema::dropIfExists('setting_tunjangan');
    }
};
