<?php

namespace App\Helpers;
use Sentinel;
use App\Services\FeederDiktiApiService;


class DosenHelper{
    public static function getListDosen()
    {
        $act = "GetListDosen";
        $limit = "1";
        // $filter = "id_prodi = '".$id_prodi."' and id_periode = '".$id_periode."'";        
        $filter = "";        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();             
        return $res['data'];
    }

    public static function getDetailBiodataDosen($id_dosen)
    {
        $act = "DetailBiodataDosen";
        $limit = "";
        $filter = "id_dosen = '".$id_dosen."'";        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $res = $data->runWS();             
        return $res['data'];
    }
    
}