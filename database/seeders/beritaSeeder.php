<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class beritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Berita::create([
            'gambar' => '...',
            'judul' => 'Blablabla',
            'deskripsi' => 'Halo gengs disini saya akan membuat sebuah deskripsi tabel berita',
        ]);

        Berita::create([
            'gambar' => '...',
            'judul' => 'lalalla',
            'deskripsi' => 'Halo gengs disini saya akan membuat sebuah deskripsi tabel berita',
        ]);
    }
}
