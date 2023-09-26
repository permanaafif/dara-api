<?php

namespace Database\Seeders;

use App\Models\GolonganDarah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class golonganDarahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GolonganDarah::create([
            'nama' => 'A'
        ]);

        GolonganDarah::create([
            'nama' => 'B'
        ]);

        GolonganDarah::create([
            'nama' => 'O'
        ]);

        GolonganDarah::create([
            'nama' => 'AB'
        ]);
    }
}
