<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\GolonganDarah;
use App\Models\Pendonor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Pendonor::create([
            'nama' => 'Afif Permana',
            'email' => 'afifpermana100902@gmail.com',
            'tanggal_lahir' => '2002-09-10',
            'kode_pendonor' => 'dara'.rand(10000, 99999),
            'jenis_kelamin' => 'laki-laki',
            'id_golongan_darah'=> 1,
            'berat_badan' => 47,
            'kontak_pendonor' => '08877541516',
            'alamat_pendonor' => 'Padang Panjang',
            'password' => bcrypt('123456789'),
            'stok_darah_tersedia' => 0,
        ]);

        Pendonor::create([
            'nama' => 'Nadya',
            'email' => 'nadya2002@gmail.com',
            'tanggal_lahir' => '2002-09-10',
            'kode_pendonor' => 'dara'.rand(10000, 99999),
            'jenis_kelamin' => 'perempuan',
            'id_golongan_darah'=> 1,
            'berat_badan' => 47,
            'kontak_pendonor' => '08877541516',
            'alamat_pendonor' => 'Padang',
            'password' => Hash::make('123456789'),
            'stok_darah_tersedia' => 0,
        ]);
    }
}
