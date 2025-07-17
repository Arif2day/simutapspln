<?php

namespace App\Helpers;
use Sentinel;
use App\Services\FeederDiktiApiService;


class NeoUmumHelper{
    public static function getAllPT($nama_pt) {        
        $act = "GetAllPT";        
        $limit = "1";
        $filter = "nama_perguruan_tinggi LIKE '%".$nama_pt."%'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        return $res['data'];    
    }

    public static function getAllProdi($ptid) {        
        $act = "GetAllProdi";
        $limit = "";
        $filter = "id_perguruan_tinggi = '".$ptid."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        return $res['data'];    
    }

    public static function getWilayah($nama_wil){
        $act = "GetWilayah";
        $limit = "";
        $filter = "nama_wilayah LIKE '%".ucwords($nama_wil)."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        
        return $res['data'];
    }

    public static function getWilayahById($id){
        $act = "GetWilayah";
        $limit = "";
        $filter = "id_wilayah = '".$id."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        
        return $res['data'];
    }

    public static function getListLevelWilayah($list_wil){
        $level['id'] = null;
        $level['code'] = null;
        $level['nama'] = null;
        $list_wilayah = array();

        foreach ($list_wil as $key => $value) {
            $split_kode = str_split($value['id_wilayah'],2);
            if($split_kode[2]!="00"){
                $level['id']=2;
                # function langsung insert ke array
                $level['code'] = $split_kode;
                $level['nama'] = $value['nama_wilayah'];
                $level = self::concatenateWilayah($level);
                array_push($list_wilayah,$level); 
            }elseif ($split_kode[1]!="00") {
                $level['id']=1;
                # function cari turunan dari level['id'] ini
                $turunan = self::findByWilayahCode($split_kode[0].$split_kode[1],$level['id']);
                # function langsung insert ke array
                foreach ($turunan as $key2 => $value2) {
                    $split_kode2 = str_split($value2['id_wilayah'],2);
                    if($split_kode2[2]!="00"){
                        $level['id']=2;
                        # function langsung insert ke array
                        $level['code'] = $split_kode2;
                        $level['nama'] = $value2['nama_wilayah'];
                        $level = self::concatenateWilayah($level);
                        array_push($list_wilayah,$level); 
                    }
                }
            }elseif ($split_kode[0]!="00") {
                $level['id']=0;
                # function cari turunan dari level['id'] ini
                $turunan = self::findByWilayahCode($split_kode[0],$level['id']);
                # function langsung insert ke array
                foreach ($turunan as $key2 => $value2) {
                    $split_kode2 = str_split($value2['id_wilayah'],2);
                    if($split_kode2[1]!="00"){
                        $level['id']=1;
                        # function langsung insert ke array
                        $level['code'] = $split_kode2;
                        $level['nama'] = $value2['nama_wilayah'];
                        $level = self::concatenateWilayah($level);
                        array_push($list_wilayah,$level); 
                    }
                }
            }elseif ($split_kode[0]=="00") {
                $level['id']=-1;
                # function cari turunan dari level['id'] ini
                $turunan = self::findByWilayahReg();
                # function langsung insert ke array
                foreach ($turunan as $key2 => $value2) {
                    $split_kode2 = str_split($value2['id_wilayah'],2);
                    if($split_kode2[0]!="00"){
                        $level['id']=0;
                        $turunan2 = self::findByWilayahCodeLimitOne($split_kode2[0]."01",$level['id']);
                        # function langsung insert ke array
                        foreach ($turunan2 as $key3 => $value3) {
                            $split_kode3 = str_split($value3['id_wilayah'],2);
                            if($split_kode3[1]!="00"){
                                $level['id']=1;
                                # function langsung insert ke array
                                $level['code'] = $split_kode3;
                                $level['nama'] = $value3['nama_wilayah'];
                                $level = self::concatenateWilayah($level);
                                array_push($list_wilayah,$level); 
                            }
                        }
                    }
                }
                # function langsung insert ke array
                $level['code'] = $split_kode;
                $level['nama'] = $value['nama_wilayah'];
                array_push($list_wilayah,$level); 
            }                                   
        }
        return $list_wilayah;
    }

    static function findByWilayahCodeLimitOne($code,$level_id){
        $act = "GetWilayah";
        $limit = "1";
        $filter = "id_wilayah LIKE '".$code."%'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        
        return $res['data'];
    }

    static function findByWilayahCode($code,$level_id){
        $act = "GetWilayah";
        $limit = "30";
        $filter = "id_wilayah LIKE '".$code."%'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        
        return $res['data'];
    }

    static function findByWilayahReg(){
        $act = "GetWilayah";
        $limit = "30";
        $filter = "id_negara = 'ID'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        
        return $res['data'];
    }

    static function concatenateWilayah($wilayah){
        if($wilayah['id']==2){
            $level['id'] = $wilayah['id'];
            $level['code'] = $wilayah['code'];
            $level['code_lengkap'] = preg_replace('/\s+/', '', join($wilayah['code']));
            $level['nama'][0] = self::findProv($wilayah['code'][0]."00"."00");
            $level['nama'][1] = self::findKabKo($wilayah['code'][0].$wilayah['code'][1]."00");
            $level['nama'][2] = $wilayah['nama'];
            $level['nama_lengkap'] = $level['nama'][2]." - ".
            $level['nama'][1]." - ".
            $level['nama'][0];
            return $level;
        }
        
        if($wilayah['id']==1){
            $level['id'] = $wilayah['id'];
            $level['code'] = $wilayah['code'];
            $level['code_lengkap'] = preg_replace('/\s+/', '', join($wilayah['code']));
            $level['nama'][0] = "Indonesia";
            $level['nama'][1] = self::findProv($wilayah['code'][0]."00"."00");
            $level['nama'][2] = $wilayah['nama'];
            $level['nama_lengkap'] = $level['nama'][2]." - ".
            $level['nama'][1]." - ".
            $level['nama'][0];
            return $level;
        }
    }

    static function findProv($code){
        $act = "GetWilayah";
        $limit = "";
        $filter = "id_wilayah = '".$code."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        
        return $res['data'][0]['nama_wilayah'];
    }

    static function findKabKo($code){
        $act = "GetWilayah";
        $limit = "";
        $filter = "id_wilayah = '".$code."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        return $res['data'][0]['nama_wilayah'];
    }

    static function findKec($code){
        $act = "GetWilayah";
        $limit = "";
        $filter = "id_wilayah = '".$code."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        
        return $res['data'][0]['nama_wilayah'];
    }

    public static function getLevelWilayah(){
        $act = "GetLevelWilayah";
        $limit = "";
        $filter = "";        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();             
        return $res['data'];
    }

    public static function getAgama()
    {
        $act = "GetAgama";
        $limit = "";
        $filter = "";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        usort($res['data'], fn($a, $b) => $a['id_agama'] <=> $b['id_agama']);
        return $res['data'];
    }

    public static function getAgamaById($id)
    {
        $act = "GetAgama";
        $limit = "";
        $filter = "id_agama = '".$id."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        return $res['data'];
    }

    public static function getNegara($nama_negara)
    {
        $act = "GetNegara";
        $limit = "";
        $filter = "nama_negara LIKE '%".$nama_negara."%'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        return $res['data'];
    }

    public static function getNegaraById($id)
    {
        $act = "GetNegara";
        $limit = "";
        $filter = "id_negara = '".$id."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        return $res['data'];
    }

    public static function getPendidikan()
    {
        $act = "GetJenjangPendidikan";
        $limit = "";
        $filter = "";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        usort($res['data'], fn($a, $b) => $a['id_jenjang_didik'] <=> $b['id_jenjang_didik']);
        return $res['data'];
    }

    public static function getPendidikanById($id)
    {
        $act = "GetJenjangPendidikan";
        $limit = "";
        $filter = "id_jenjang_didik = '".$id."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        return $res['data'];
    }
    
    public static function getPekerjaan()
    {
        $act = "GetPekerjaan";
        $limit = "";
        $filter = "";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        usort($res['data'], fn($a, $b) => $a['id_pekerjaan'] <=> $b['id_pekerjaan']);
        return $res['data'];
    }

    public static function getPekerjaanById($id)
    {
        $act = "GetPekerjaan";
        $limit = "";
        $filter = "id_pekerjaan = '".$id."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        return $res['data'];
    }

    public static function getPenghasilan()
    {
        $act = "GetPenghasilan";
        $limit = "";
        $filter = "";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        usort($res['data'], fn($a, $b) => $a['id_penghasilan'] <=> $b['id_penghasilan']);
        return $res['data'];
    }

    public static function getPenghasilanById($id)
    {
        $act = "GetPenghasilan";
        $limit = "";
        $filter = "id_penghasilan = '".$id."'";  
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    
        return $res['data'];
    }

    public static function insertBiodataMahasiswa($param)
    {
        $act = "InsertBiodataMahasiswa";
        $limit = "";
        $filter = "";
        $record = [            
            "nama_mahasiswa"=> $param->nama_mahasiswa,
            "jenis_kelamin"=> $param->jenis_kelamin,
            "tempat_lahir"=> $param->tempat_lahir,
            "tanggal_lahir"=> $param->tanggal_lahir,
            "id_agama"=> $param->id_agama,
            "nik"=> $param->nik,
            "nisn"=> $param->nisn,
            "npwp"=> null,
            "kewarganegaraan"=> $param->kewarganegaraan,
            "jalan"=> $param->jalan,
            "dusun"=> $param->dusun,
            "rt"=> $param->rt,
            "rw"=> $param->rw,
            "kelurahan"=> $param->kelurahan,
            "kode_pos"=> $param->kode_pos,
            "id_wilayah"=> $param->id_wilayah,
            "id_jenis_tinggal"=> null,
            "id_alat_transportasi"=> null,
            "telepon"=> null,
            "handphone"=> $param->handphone,
            "email"=> $param->email,
            "penerima_kps"=> $param->is_kps,
            "nomor_kps"=> $param->no_kps,
            "nik_ayah"=> $param->nik_ayah,
            "nama_ayah"=> $param->nama_ayah,
            "tanggal_lahir_ayah"=> $param->tanggal_lahir_ayah,
            "id_pendidikan_ayah"=> $param->id_pendidikan_ayah,
            "id_pekerjaan_ayah"=> $param->id_pekerjaan_ayah,
            "id_penghasilan_ayah"=> $param->id_penghasilan_ayah,
            "nik_ibu"=> $param->nik_ibu,
            "nama_ibu_kandung"=> $param->nama_ibu_kandung,
            "tanggal_lahir_ibu"=> $param->tanggal_lahir_ibu,
            "id_pendidikan_ibu"=> $param->id_pendidikan_ibu,
            "id_pekerjaan_ibu"=> $param->id_pekerjaan_ibu,
            "id_penghasilan_ibu"=> $param->id_penghasilan_ibu,
            "nama_wali"=> null,
            "tanggal_lahir_wali"=> null,
            "id_pendidikan_wali"=> null,
            "id_pekerjaan_wali"=> null,
            "id_penghasilan_wali"=> null,
            "id_kebutuhan_khusus_mahasiswa"=> 0,
            "id_kebutuhan_khusus_ayah"=> 0,
            "id_kebutuhan_khusus_ibu"=> 0
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
}
