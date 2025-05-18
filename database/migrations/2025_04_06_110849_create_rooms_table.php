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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('no_kamar');
            $table->string('img');
            $table->enum('jenis_kamar', ['deluxe', 'suite', 'standard']);
            $table->string('harga_per_malam');
            $table->json('fasilitas');
            $table->enum('status', ['tersedia', 'terisi', 'booking'])->default('tersedia');
            $table->integer('max_tamu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
