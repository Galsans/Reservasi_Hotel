<?php

namespace Database\Seeders;

use App\Models\Rooms;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisKamarList = ['suite', 'deluxe', 'standard'];

        for ($i = 1; $i <= 15; $i++) {
            $jenis_kamar = $jenisKamarList[array_rand($jenisKamarList)];

            // Harga per malam
            $harga = match ($jenis_kamar) {
                'suite' => rand(800000, 1500000),
                'deluxe' => rand(400000, 700000),
                'standard' => rand(100000, 300000),
            };

            // Fasilitas
            $fasilitas = match ($jenis_kamar) {
                'suite' => [
                    "Tempat tidur king size",
                    "Ruang tamu terpisah",
                    "Jacuzzi",
                    "Mini bar",
                    "Smart TV",
                    "Wi-Fi gratis",
                    "Layanan kamar 24 jam"
                ],
                'deluxe' => [
                    "Tempat tidur queen size",
                    "TV kabel",
                    "AC",
                    "Mini bar",
                    "Wi-Fi gratis",
                    "Kamar mandi pribadi dengan shower"
                ],
                'standard' => [
                    "Tempat tidur double",
                    "TV",
                    "Kipas angin atau AC",
                    "Wi-Fi gratis",
                    "Kamar mandi dalam"
                ],
            };

            $tamu = match ($jenis_kamar) {
                'suite' => 7,
                'deluxe' => 5,
                'standard' => 3,
            };

            // Simpan ke database
            $room = Rooms::create([
                'no_kamar' => 'TEMP', // placeholder
                'img' => 'rooms/p8wGtd7zgdRm7qQ82kWUSZNLrHBKjJllSjlc2Avv.jpg', // kamu bisa ubah ke gambar acak juga
                'jenis_kamar' => $jenis_kamar,
                'harga_per_malam' => $harga,
                'fasilitas' => json_encode($fasilitas),
                'status' => 'tersedia',
                'max_tamu' => $tamu
            ]);

            // Update no_kamar berdasarkan ID
            $room->update([
                'no_kamar' => 'K-' . $room->id
            ]);
        }
    }
}
