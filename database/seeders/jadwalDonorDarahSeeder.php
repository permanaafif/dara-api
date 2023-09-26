<?php

namespace Database\Seeders;

use App\Models\Jadwal_donor_darah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class jadwalDonorDarahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jadwal_donor_darah::create([
            'lokasi' => 'Tabing',
            'alamat' => 'M Thalin',
            'tanggal_donor' => '2023-07-15',
            'jam_mulai' => '09:00',
            'jam_selesai' => '15:00',
            'kontak' => '082235221661',
            'latitude' => 121.4567890,
            'longitude' => 15.6789012,
        ]);

        Jadwal_donor_darah::create([
            'lokasi' => 'Tabing2',
            'alamat' => 'M Thalin',
            'tanggal_donor' => '2023-07-15',
            'jam_mulai' => '09:00',
            'jam_selesai' => '15:00',
            'kontak' => '082235221661',
            'latitude' => 121.4567890,
            'longitude' => 15.6789012,
        ]);
    }
}
