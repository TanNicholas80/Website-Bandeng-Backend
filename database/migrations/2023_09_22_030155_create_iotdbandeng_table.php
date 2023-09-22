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
        Schema::create('iotdbandeng', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // FOREIGN KEY to Mitra
            $table->string('power')->default('OFF');
            $table->string('panjang');
            $table->string('berat');
            $table->string('harga');
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
        Schema::dropIfExists('iotdbandeng');
    }
};
