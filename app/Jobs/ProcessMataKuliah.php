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
use App\Models\MataKuliah;
use Sentinel;

class ProcessMataKuliah implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mataKuliah;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mataKuliah)
    {
        $this->mataKuliah = $mataKuliah;
        // dd($this->userMahasiswaData);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->mataKuliah as $mataKuliah)
        {       
            //prepare data mata kuliah to job
            $check = MataKuliah::where('id_matkul','=',$mataKuliah['id_matkul'])->get();
            if(count($check)==0){
                // insert
                $kk = new MataKuliah();
                $kk->id_jenj_didik = $mataKuliah['id_jenj_didik'];
                $kk->tgl_create = $mataKuliah['tgl_create'];
                $kk->id_matkul = $mataKuliah['id_matkul'];
                $kk->jns_mk = $mataKuliah['jns_mk'];
                $kk->kel_mk = $mataKuliah['kel_mk'];
                $kk->kode_mata_kuliah = $mataKuliah['kode_mata_kuliah'];
                $kk->nama_mata_kuliah = $mataKuliah['nama_mata_kuliah'];
                $kk->sks_mata_kuliah = $mataKuliah['sks_mata_kuliah'];
                $kk->id_prodi = $mataKuliah['id_prodi'];
                $kk->nama_program_studi = $mataKuliah['nama_program_studi'];
                $kk->id_jenis_mata_kuliah = $mataKuliah['id_jenis_mata_kuliah'];
                $kk->id_kelompok_mata_kuliah = $mataKuliah['id_kelompok_mata_kuliah'];
                $kk->sks_tatap_muka = $mataKuliah['sks_tatap_muka'];
                $kk->sks_praktek = $mataKuliah['sks_praktek'];
                $kk->sks_praktek_lapangan = $mataKuliah['sks_praktek_lapangan'];
                $kk->sks_simulasi = $mataKuliah['sks_simulasi'];
                $kk->metode_kuliah = $mataKuliah['metode_kuliah'];
                $kk->ada_sap = $mataKuliah['ada_sap'];
                $kk->ada_silabus = $mataKuliah['ada_silabus'];
                $kk->ada_bahan_ajar = $mataKuliah['ada_bahan_ajar'];
                $kk->ada_acara_praktek = $mataKuliah['ada_acara_praktek'];
                $kk->ada_diktat = $mataKuliah['ada_diktat'];
                $kk->tanggal_mulai_efektif = $mataKuliah['tanggal_mulai_efektif'];
                $kk->tanggal_selesai_efektif = $mataKuliah['tanggal_selesai_efektif'];
                $kk->save();
            }else{
                // update
                $kk = MataKuliah::where('id_matkul','=',$mataKuliah['id_matkul'])->first();
                $kk->id_jenj_didik = $mataKuliah['id_jenj_didik'];
                $kk->tgl_create = $mataKuliah['tgl_create'];
                $kk->id_matkul = $mataKuliah['id_matkul'];
                $kk->jns_mk = $mataKuliah['jns_mk'];
                $kk->kel_mk = $mataKuliah['kel_mk'];
                $kk->kode_mata_kuliah = $mataKuliah['kode_mata_kuliah'];
                $kk->nama_mata_kuliah = $mataKuliah['nama_mata_kuliah'];
                $kk->sks_mata_kuliah = $mataKuliah['sks_mata_kuliah'];
                $kk->id_prodi = $mataKuliah['id_prodi'];
                $kk->nama_program_studi = $mataKuliah['nama_program_studi'];
                $kk->id_jenis_mata_kuliah = $mataKuliah['id_jenis_mata_kuliah'];
                $kk->id_kelompok_mata_kuliah = $mataKuliah['id_kelompok_mata_kuliah'];
                $kk->sks_tatap_muka = $mataKuliah['sks_tatap_muka'];
                $kk->sks_praktek = $mataKuliah['sks_praktek'];
                $kk->sks_praktek_lapangan = $mataKuliah['sks_praktek_lapangan'];
                $kk->sks_simulasi = $mataKuliah['sks_simulasi'];
                $kk->metode_kuliah = $mataKuliah['metode_kuliah'];
                $kk->ada_sap = $mataKuliah['ada_sap'];
                $kk->ada_silabus = $mataKuliah['ada_silabus'];
                $kk->ada_bahan_ajar = $mataKuliah['ada_bahan_ajar'];
                $kk->ada_acara_praktek = $mataKuliah['ada_acara_praktek'];
                $kk->ada_diktat = $mataKuliah['ada_diktat'];
                $kk->tanggal_mulai_efektif = $mataKuliah['tanggal_mulai_efektif'];
                $kk->tanggal_selesai_efektif = $mataKuliah['tanggal_selesai_efektif'];
                $kk->save();
            }
        }
    }
}
