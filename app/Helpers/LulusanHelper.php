<?php

namespace App\Helpers;
use Sentinel;
use App\Services\FeederDiktiApiService;


class LulusanHelper{
    public static function exportDataMahasiswaLulus()
    {
        $act = "ExportDataMahasiswaLulus";
        $limit = "";
        $filter = "id_jenis_keluar = '1' ";//and id_periode = '".$id_periode."'";        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();             
        return $res['data'];
    }

    public static function getListMahasiswaLulusDOforAnalisis($id_semester,$id_prodis,$tidak_lulus)
    {
        $id_periode_keluar = "";
        foreach ($id_semester as $key => $value) {
            $id_periode_keluar = $id_periode_keluar."'".$value."',"; 
        }
        $id_periode_keluar = rtrim($id_periode_keluar, ",");

        $id_prodi = "";
        $fil = "";
        if($id_prodis == null){
            if($tidak_lulus=="tidak-lulus"){
                $fil = "id_jenis_keluar != '1'  and id_periode_keluar in (".$id_periode_keluar.")";
            }else{
                $fil = "id_jenis_keluar = '1'  and id_periode_keluar in (".$id_periode_keluar.")";
            }
        }else{
        foreach ($id_prodis as $key => $value) {
            $id_prodi = $id_prodi."'".$value."',"; 
        }
        $id_prodi = rtrim($id_prodi, ",");
            if($tidak_lulus=="tidak-lulus"){
                $fil = "id_jenis_keluar != '1'  and id_periode_keluar in (".$id_periode_keluar.") and id_prodi in (".$id_prodi.")";

            }else{            
                $fil = "id_jenis_keluar = '1'  and id_periode_keluar in (".$id_periode_keluar.") and id_prodi in (".$id_prodi.") ";
            }
        }

        $act = "GetListMahasiswaLulusDO";
        $limit = "";
        $filter = $fil;        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();    

        return $res['data'];
    }

    public static function getListMahasiswaLulusDO($id_prodi,$status="all",$tahun_lulus="all")
    {
        $act = "GetListMahasiswaLulusDO";
        $limit = "";
        $filter = "id_prodi = '".$id_prodi."'";        
        if($status!="all"){
            $filter = $filter. " and nama_jenis_keluar = '".$status."'";
        }
        if($tahun_lulus!="all"){
            $filter = $filter. " and tanggal_keluar LIKE '%".$tahun_lulus."'";
        }        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();             
        return $res['data'];
    }    
}