<?php

namespace App\Helpers;
use Sentinel;
use App\Services\FeederDiktiApiService;
use App\Models\Users;

use Arr;
class InsertPesertaKelasKuliahHelper{
    
    public static function insert($id_reg_mhs,$id_kelas_kuliah){
        $act = "InsertPesertaKelasKuliah";
        $limit = "";
        $filter = "";
        $record = [
            "id_kelas_kuliah" => $id_kelas_kuliah,
            "id_registrasi_mahasiswa" => $id_reg_mhs
        ];
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);

        try{
            $data = $data->postWS();
            return $data;
        }catch(Exception $e){
            Log::error($e);
        }
    }

    public static function updateNilai($id_reg_mhs,$id_kelas_kuliah,$nilai_angka,$nilai_index,$nilai_huruf){
        $act = "UpdateNilaiPerkuliahanKelas";
        $limit = "";
        $filter = "";
        $record = [
            "nilai_angka" => $nilai_angka,
            "nilai_indeks" => $nilai_index,
            "nilai_huruf" => $nilai_huruf
        ];
        $key = [
            "id_kelas_kuliah" => $id_kelas_kuliah,
            "id_registrasi_mahasiswa" => $id_reg_mhs
        ];
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);

        try{
            $data = $data->updateWS();
            return $data;
        }catch(Exception $e){
            Log::error($e);
        }
    }

    public static function resetNilai($id_reg_mhs,$id_kelas_kuliah){
        $act = "UpdateNilaiPerkuliahanKelas";
        $limit = "";
        $filter = "";
        $record = [
            // "nilai_angka" => "NULL",
            "nilai_angka" => "null",
            "nilai_indeks" => "0",
            // "nilai_indeks" => null,
            "nilai_huruf" => "E",
            // "nilai_huruf" => " ",
            // "filter_nilai_huruf" => "",
            // "colum"	=>"b (3,00)",
            // "nilai_angka_masking" => "",
            // "sof_delete" => "0",
        ];
        $key = [
            "id_kelas_kuliah" => $id_kelas_kuliah,
            "id_registrasi_mahasiswa" => $id_reg_mhs
        ];
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);

        try{
            $data = $data->updateWS();
            return $data;
        }catch(Exception $e){
            Log::error($e);
        }
    }

    public static function delete($id_reg_mhs,$id_kelas){        
        $act = "DeletePesertaKelasKuliah";
        $limit = "";
        $filter = "";
        $record = "";
        $key = [
            "id_kelas_kuliah" => $id_kelas,
            "id_registrasi_mahasiswa" => $id_reg_mhs
        ];
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);

        try{
            $data = $data->deleteWS();
            return $data;
        }catch(Exception $e){
            Log::error($e);
        }
    }

    public static function getKelasKuliah($id_prodi,$id_semester)
    {
        try {
            $act = "GetListKelasKuliah";
            $limit = "";
            $filter = "id_semester = '".$id_semester."' and id_prodi = '".$id_prodi."'";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $ress = $data->runWS();

            $res = array();
            foreach ($ress['data'] as $key => $value) {
                $temp = array();
                $act = "GetCountPesertaKelasKuliah";
                $limit = "";
                $filter ="id_kelas_kuliah = '".$value['id_kelas_kuliah']."'";
                $record = "";
                
                $key = "";
                $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
                $ress = $data->runWS();
                
                $value = Arr::add($value, 'jml_mhs', $ress['data']);
                array_push($res,$value);             
            }

            $sort = array();
            foreach($res as $k=>$v) {
                $sort['nama_kelas_kuliah'][$k] = $v['nama_kelas_kuliah'];
            }
            array_multisort($sort['nama_kelas_kuliah'], SORT_ASC,$res);
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }
        return $res;
    }

    public static function getNilaiKuliah($nama_prodi,$id_semester)
    {
        try {
            $act = "GetListNilaiPerkuliahanKelas";
            $limit = "";
            $filter = "id_smt = '".$id_semester."'"
            ." and nama_prodi = '".$nama_prodi."'";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();              
            $sort = array();
            foreach($res['data'] as $k=>$v) {
            $sort['nama_kelas_kuliah'][$k] = $v['nama_kelas_kuliah'];
            }
            array_multisort($sort['nama_kelas_kuliah'], SORT_ASC,$res['data']);


            $temp_perc = 0;
            foreach ($res['data'] as $key => $value) {
                $jumlah_null = count(self::getNullNilaiPerkuliahanKelas($value['id_kelas_kuliah']));
                $res['data'][$key]['jml_null'] = $jumlah_null;
                
                $jml = $value['jumlah_mahasiswa_krs'];
                $jml_dinilai = $value['jumlah_mahasiswa_dapat_nilai']-$jumlah_null;
                if($jml==0){
                    $perc=0;
                }else{
                    $perc = $jml_dinilai/$jml*100;
                }
                $temp_perc = $temp_perc + $perc;
            }
            $jml_record = count($res['data']);
            if($temp_perc==0){
                $whole_perc = 0;
            }else{
                $whole_perc = $temp_perc/($jml_record*100)*100; 
            }
            foreach ($res['data'] as $key => $value) {
                $res['data'][$key]['whole_perc']=$whole_perc;
            }
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }
        return $res;
    }

    public static function getOnlyProgressPercentageNilaiKuliah($nama_prodi,$id_semester)
    {
        try {
            $act = "GetListNilaiPerkuliahanKelas";
            $limit = "";
            $filter = "id_smt = '".$id_semester."'"
            ." and nama_prodi = '".$nama_prodi."'";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();              
            $sort = array();
            foreach($res['data'] as $k=>$v) {
            $sort['nama_kelas_kuliah'][$k] = $v['nama_kelas_kuliah'];
            }
            array_multisort($sort['nama_kelas_kuliah'], SORT_ASC,$res['data']);


            $temp_perc = 0;
            foreach ($res['data'] as $key => $value) {
                $jumlah_null = count(self::getNullNilaiPerkuliahanKelas($value['id_kelas_kuliah']));
                $res['data'][$key]['jml_null'] = $jumlah_null;
                
                $jml = $value['jumlah_mahasiswa_krs'];
                $jml_dinilai = $value['jumlah_mahasiswa_dapat_nilai']-$jumlah_null;
                if($jml==0){
                    $perc=0;
                }else{
                    $perc = $jml_dinilai/$jml*100;
                }
                $temp_perc = $temp_perc + $perc;
            }
            $jml_record = count($res['data']);
            if($temp_perc==0){
                $whole_perc = 0;
            }else{
                $whole_perc = $temp_perc/($jml_record*100)*100; 
            }

            $res['data'] = [
                "nama_prodi"=>$res['data'][$key]['nama_prodi'],
                "whole_perc"=>$whole_perc
            ];
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }
        return $res;
    }

    public static function getKRSDiambil($id_reg_mhs, $id_periode)
    {
        try {
            $act = "GetKRSMahasiswa";
            $limit = "";
            $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."' and id_periode = '".$id_periode."'";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();  
            $sorts = array();
            foreach($res['data'] as $k=>$v) {
            $sorts['nama_mata_kuliah'][$k] = $v['nama_mata_kuliah'];
            }
            if($res['data']){
                array_multisort($sorts['nama_mata_kuliah'], SORT_ASC,$res['data']);
            }
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }
        return $res;
    }

    public static function getDetailKelasKuliah($id_kelas_kuliah)
    {
        try {
            $act = "GetDetailKelasKuliah";
            $limit = "";
            $filter = "id_kelas_kuliah = '".$id_kelas_kuliah."'";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();  
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }
        return $res['data'][0];        
    }

    public static function getDetailNilaiPerkuliahanKelas($id_kelas_kuliah)
    {
        try {
            $act = "GetDetailNilaiPerkuliahanKelas";
            $limit = "";
            $filter = "id_kelas_kuliah = '".$id_kelas_kuliah."'";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();  

            $sort = array();
            foreach($res['data'] as $k=>$v) {
            $sort['nim'][$k] = $v['nim'];
            }
            array_multisort($sort['nim'], SORT_ASC,$res['data']);
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }
        return $res['data'];        
    }

    static function getNullNilaiPerkuliahanKelas($id_kelas_kuliah)
    {
        try {
            $act = "GetDetailNilaiPerkuliahanKelas";
            $limit = "";
            $filter = "id_kelas_kuliah = '".$id_kelas_kuliah."' and nilai_angka is null and nilai_huruf is not null";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();  
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }
        return $res['data'];
    }

    public static function getDosenPengajarKelasKuliah($id_kelas_kuliah)
    {
        try {
            $act = "GetDosenPengajarKelasKuliah";
            $limit = "";
            $filter = "id_kelas_kuliah = '".$id_kelas_kuliah."'";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();  
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }
        return $res['data']; 
    }

    public static function getPesertaKelasKuliah($id_kelas_kuliah)
    {
        try {
            $act = "GetPesertaKelasKuliah";
            $limit = "";
            $filter = "id_kelas_kuliah = '".$id_kelas_kuliah."'";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();  
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }

        $data = $res['data'];

        foreach ($data as $key => $value) {
            $detail = Users::where('id_registrasi_mahasiswa',$value['id_registrasi_mahasiswa'])->first();
            $gender =json_decode($detail->data_mahasiswa,true)['jenis_kelamin'];
            $data[$key]['gender'] = $gender;
        }

        return $data; 
    }

    public static function getListSkalaNilaiProdi($id_prodi)
    {
        try {
            $act = "GetListSkalaNilaiProdi";
            $limit = "";
            $filter = "id_prodi = '".$id_prodi."'";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();  

            $sort = array();
            foreach($res['data'] as $k=>$v) {
            $sort['bobot_maksimum'][$k] = $v['bobot_maksimum'];
            }
            array_multisort($sort['bobot_maksimum'], SORT_DESC,$res['data']);
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }

        $data = $res['data'];

        return $data; 
    }

    public static function getPeriode($id_prodi)
    {
        try {
            $act = "GetPeriode";
            $limit = "";
            $filter = "id_prodi = '".$id_prodi."'";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();  
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }

        $data = $res['data'];

        return $data; 
    }
    
    public static function getListRencanaEvaluasi(){
        try {
            $act = "GetListRencanaEvaluasi";
            $limit = "";
            // $filter = "id_prodi = '".$id_prodi."'";
            $filter = "";
            $record = "";

            $key = "";
            $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
            $res = $data->runWS();  

            $sort = array();
            // foreach($res['data'] as $k=>$v) {
            // $sort['bobot_maksimum'][$k] = $v['bobot_maksimum'];
            // }
            // array_multisort($sort['bobot_maksimum'], SORT_DESC,$res['data']);
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }

        $data = $res['data'];

        return $data; 
    }
}