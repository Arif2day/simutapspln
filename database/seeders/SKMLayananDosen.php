<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SKMLayananDosen extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 45 ; $i++) { 
            
        DB::table('survei_kepuasan_mahasiswa_layanan_dosen')->insert([
            'id_registrasi_mahasiswa' => 'x',
            'id_semester' => '20222',
            'id_prodi' => 'e28c160a-194d-4aad-a3fd-95e14b065c86',
            'a1' => rand(1,5),
            'a2' => rand(1,5),
            'a3' => rand(1,5),
            'a4' => rand(1,5),
            'a5' => rand(1,5),
            'b1' => rand(1,5),
            'b2' => rand(1,5),
            'b3' => rand(1,5),
            'b4' => rand(1,5),
            'b5' => rand(1,5),
            'b6' => rand(1,5),
            'c1' => rand(1,5),
            'c2' => rand(1,5),
            'c3' => rand(1,5)
        ]);
    }
    }
}
