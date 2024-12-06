<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeAkademikSeeder extends Seeder
{
    public function run()
    {
        // Data periode akademik
        $periode = [
            [
                'id_periode' => '23242',
                'nama_periode' => 'Semester Akademik 2023/2024 Genap',
                'tahun_mulai' => '2024-08-03 00:00:00',
                'tahun_selesai' => '2024-12-31 23:59:59',
                'jenis' => 'genap',
            ],
            [
                'id_periode' => '24251',
                'nama_periode' => 'Semester Akademik 2024/2025 Ganjil',
                'tahun_mulai' => '2024-09-10 00:00:00', // Updated start date
                'tahun_selesai' => '2025-06-10 23:59:59',
                'jenis' => 'ganjil',
            ]
        ];

        // Insert data periode akademik
        DB::table('periode_akademik')->insert($periode);
    }
}
