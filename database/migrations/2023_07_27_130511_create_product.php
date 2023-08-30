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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // FOREIGN KEY to Mitra
            $table->uuid('mitra_id');
            $table->foreign('mitra_id')->references('id')->on('mitras');
            $table->string('nmProduk');
            $table->integer('hrgProduk');
            $table->integer('stok');
            $table->string('foto_produk')->nullable();
            $table->string('beratProduk');
            $table->string('dskProduk');
            $table->string('link');
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
        Schema::dropIfExists('product');
    }
};
