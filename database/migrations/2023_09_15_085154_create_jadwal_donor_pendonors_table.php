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
        Schema::create('jadwal_donor_pendonors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pendonor')->nullable(false);
            $table->unsignedBigInteger('id_jadwal_donor_darah')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_donor_pendonors');
    }
};
