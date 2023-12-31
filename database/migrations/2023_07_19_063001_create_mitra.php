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
        Schema::create('mitras', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('namaLengkap')->nullable();
            $table->string('namaMitra');
            $table->string('alamatMitra');
            $table->date('tglLahir')->nullable();
            $table->string('jeniskel')->nullable();
            $table->string('no_tlp');
            $table->string('foto_mitra')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('resetPassToken')->nullable();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mitra');
    }
};
