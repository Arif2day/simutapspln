<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Users;
use App\Models\KelasKuliah;
use Sentinel;

class ProcessKelasKuliah implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $kelasKuliah;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($kelasKuliah)
    {
        $this->kelasKuliah = $kelasKuliah;
        // dd($this->userMahasiswaData);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->kelasKuliah as $kelasKuliah)
        {       
            //prepare data kelas kuliah to job
            $check = KelasKuliah::where('id_kelas_kuliah','=',$kelasKuliah['id_kelas_kuliah'])->get();
            if(count($check)==0){
                // insert
                $kk = new KelasKuliah();
                $kk->id_kelas_kuliah = $kelasKuliah['id_kelas_kuliah'];
                $kk->id_prodi = $kelasKuliah['id_prodi'];
                $kk->nama_program_studi = $kelasKuliah['nama_program_studi'];
                $kk->id_semester = $kelasKuliah['id_semester'];
                $kk->nama_semester = $kelasKuliah['nama_semester'];
                $kk->id_matkul = $kelasKuliah['id_matkul'];
                $kk->kode_mata_kuliah = $kelasKuliah['kode_mata_kuliah'];
                $kk->nama_mata_kuliah = $kelasKuliah['nama_mata_kuliah'];
                $kk->nama_kelas_kuliah = $kelasKuliah['nama_kelas_kuliah'];
                $kk->sks = $kelasKuliah['sks'];
                $kk->id_dosen = $kelasKuliah['id_dosen'];
                $kk->nama_dosen = $kelasKuliah['nama_dosen'];
                $kk->jumlah_mahasiswa = $kelasKuliah['jumlah_mahasiswa'];
                $kk->apa_untuk_pditt = $kelasKuliah['apa_untuk_pditt'];
                $kk->save();
            }else{
                // update                
                $kk = KelasKuliah::where('id_kelas_kuliah','=',$kelasKuliah['id_kelas_kuliah'])->first();
                $kk->id_prodi = $kelasKuliah['id_prodi'];
                $kk->nama_program_studi = $kelasKuliah['nama_program_studi'];
                $kk->id_semester = $kelasKuliah['id_semester'];
                $kk->nama_semester = $kelasKuliah['nama_semester'];
                $kk->id_matkul = $kelasKuliah['id_matkul'];
                $kk->kode_mata_kuliah = $kelasKuliah['kode_mata_kuliah'];
                $kk->nama_mata_kuliah = $kelasKuliah['nama_mata_kuliah'];
                $kk->nama_kelas_kuliah = $kelasKuliah['nama_kelas_kuliah'];
                $kk->sks = $kelasKuliah['sks'];
                $kk->id_dosen = $kelasKuliah['id_dosen'];
                $kk->nama_dosen = $kelasKuliah['nama_dosen'];
                $kk->jumlah_mahasiswa = $kelasKuliah['jumlah_mahasiswa'];
                $kk->apa_untuk_pditt = $kelasKuliah['apa_untuk_pditt'];
                $kk->save();
            }
        }
    }
}
