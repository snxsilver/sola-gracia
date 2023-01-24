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
        Schema::create('mandor_tukang', function (Blueprint $table) {
            $table->id();
            $table->integer('mandor_proyek');
            $table->string('nama');
            $table->integer('tukang');
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
        Schema::dropIfExists('mandor_tukang');
    }
};
