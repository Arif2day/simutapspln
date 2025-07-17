<?php

namespace App\Helpers;
use Sentinel;
use App\Services\FeederDiktiApiService;


class AKMHelper{
    public static function exportDataAktivitasKuliah($id_prodi,$id_periode)
    {
        $act = "ExportDataAktivitasKuliah";
        $limit = "";
        $filter = "id_prodi = '".$id_prodi."' and id_periode = '".$id_periode."'";        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();             
        return $res['data'];
    }

    public static function exportDataAktivitasKuliahALL($id_periode)
    {
        $act = "ExportDataAktivitasKuliah";
        $limit = "";
        $filter = "id_periode = '".$id_periode."'";        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();             
        return $res['data'];
    }
    
    public static function getStatusMahasiswa(){
        $act = "GetStatusMahasiswa";
        $limit = "";
        $filter = "";        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();             
        return $res['data'];
    }

    public static function check($id_reg_mhs,$id_periode)
    {
        $status_akm['flag'] = false;
        $status_akm['data'] = null;
        $act = "GetListPerkuliahanMahasiswa";
        $limit = "1";
        $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."' and id_semester = '".$id_periode."'";        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();
        if(count($res['data'])!=0){
            $status_akm['flag']=true;
            $status_akm['data']=$res['data'];
        }                    
        return $status_akm;
    }

    public static function getPembiayaan()
    {
        $act = "GetPembiayaan";
        $limit = "";
        $filter = "";        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();  
        return $res['data'];
    }
    

    public static function insert($id_reg_mhs,$id_periode,$id_status_mahasiswa,$ips,$ipk,$sks_s,$sks_t,$biaya,$id_pembiayaan)
    {
        $act = "InsertPerkuliahanMahasiswa";
        $limit = "";
        $filter = "";
        $record = [
            "id_registrasi_mahasiswa" => $id_reg_mhs,
            "id_semester" => $id_periode,
            "id_status_mahasiswa" => $id_status_mahasiswa,
            "ips" => $ips,
            "ipk" => $ipk,
            "sks_semester" => $sks_s,
            "total_sks" => $sks_t,
            "biaya_kuliah_smt" => $biaya,
            "id_pembiayaan" => $id_pembiayaan
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

    public static function update($id_reg_mhs,$id_periode,$id_status_mahasiswa,$ips,$ipk,$sks_s,$sks_t,$biaya,$id_pembiayaan)
    {
        $act = "UpdatePerkuliahanMahasiswa";
        $limit = "";
        $filter = "";
        $record = [
            "id_status_mahasiswa" => $id_status_mahasiswa,
            "ips" => $ips,
            "ipk" => $ipk,
            "sks_semester" => $sks_s,
            "total_sks" => $sks_t,
            "biaya_kuliah_smt" => $biaya,
            "id_pembiayaan" => $id_pembiayaan
        ];
        $key = [            
            "id_registrasi_mahasiswa" => $id_reg_mhs,
            "id_semester" => $id_periode,
        ];
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);

        try{
            $data = $data->updateWS();
            return $data;
        }catch(Exception $e){
            Log::error($e);
        }
    }

    public static function delete($id_reg_mhs,$id_periode)
    {
        $act = "DeletePerkuliahanMahasiswa";
        $limit = "";
        $filter = "";
        $record = "";
        $key = [
            "id_registrasi_mahasiswa" => $id_reg_mhs,
            "id_semester" => $id_periode
        ];
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);

        try{
            $data = $data->deleteWS();
            return $data;
        }catch(Exception $e){
            Log::error($e);
        }
    }
}