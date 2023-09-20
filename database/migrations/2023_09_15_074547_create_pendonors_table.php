<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendonors', function (Blueprint $table) {
            $table->id();
            $table->string("gambar",255)->nullable();
            $table->string('nama', 100)->nullable(false);
            $table->string('kode_pendonor', 10)->nullable(false)->unique();
            $table->date('tanggal_lahir')->nullable(false);
            $table->enum('jenis_kelamin',['laki-laki','perempuan'])->nullable(false);
            $table->unsignedBigInteger('id_golongan_darah')->nullable(false);
            $table->integer('berat_badan')->nullable(false);
            $table->string('kontak_pendonor', 20)->nullable(false);
            $table->string('alamat_pendonor', 100)->nullable(false);
            $table->string('password', 100)->nullable(false);
            $table->integer('stok_darah_tersedia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendonors');
    }
};
