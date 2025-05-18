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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rooms_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->string('kode_reservation');
            $table->string('nama');
            $table->string('email');
            $table->string('nomor_telepon');
            $table->integer('jumlah_tamu');
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('total_biaya', 12, 2);
            $table->enum('status', ['menunggu pembayaran', 'terkonfirmasi', 'check_out'])->default('menunggu pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
