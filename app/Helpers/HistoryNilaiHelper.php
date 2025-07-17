<?php

namespace App\Helpers;
use App\Models\HistoryNilai;
use App\Models\TranskripNilai;
use App\Models\MataKuliah;
use Sentinel;
use App\Services\FeederDiktiApiService;
use App\Models\ValidasiKRS;
use App\Helpers\ProfilMhsHelper;

use Illuminate\Support\Arr;


class HistoryNilaiHelper{
    
    #refactored code
    public static function initHistoryNilai($id_reg_mhs,$id_periode_mhs){
        $act = "GetRiwayatNilaiMahasiswa";
        $limit = "";
        $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."'";
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);

        $dataFromjson = array();
        $periode = array();
        $smt_exists = array();
        $temp = array();

        $hisNilai = HistoryNilai::where('id_registrasi_mahasiswa','=',$id_reg_mhs)->first();

        try{
            if($hisNilai!=null){
                $hisNil = HistoryNilai::where('id_registrasi_mahasiswa','=',$id_reg_mhs)->first();

                #get periode and smt_exists
                foreach(json_decode($hisNil->history_nilai,true) as $idper)
                {
                    $dataFromjson[substr(strval($idper['id_periode']), 0, -1)] = true;
                    if($idper['id_periode']>=$id_periode_mhs){
                        $smt_exists[$idper['id_periode']] = true;
                    }
                }
                $dataFromjson = array_keys($dataFromjson);
                for($x=0;$x-count($dataFromjson);$x++){
                    $curper = $dataFromjson[$x];
                    $periode[$curper]=$curper.'/'.($curper+1);
                }

                $smt_exists = array_keys($smt_exists);
            }else{
                $res = $data->runWS(); 
                $hisN = new HistoryNilai();
                $hisN->id_registrasi_mahasiswa = $id_reg_mhs;
                $hisN->history_nilai = json_encode($res['data']);
                $hisN->save();

                #get periode and smt_exists
                foreach($res['data'] as $idper)
                {
                    $dataFromjson[substr(strval($idper['id_periode']), 0, -1)] = true;
                    if($idper['id_periode']>=$id_periode_mhs){
                        $smt_exists[$idper['id_periode']] = true;
                    }
                }
                $dataFromjson = array_keys($dataFromjson);
                for($x=0;$x-count($dataFromjson);$x++){
                    $curper = $dataFromjson[$x];
                    $periode[$curper]=$curper.'/'.($curper+1);
                }
                $smt_exists = array_keys($smt_exists);
            }
        }catch(Exception $e){
            Log::error($e);
        }

        $result['periode']=$periode;
        $result['smt_exists']=$smt_exists;
        return ($result);
    }

    #refactored code
    public static function getKHS($id_mhs,$id_reg_mhs,$id_periode)
    {                  
        $act = "GetRiwayatNilaiMahasiswa";
        $limit = "";
        $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."' and id_periode='".$id_periode."'";
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $resy = $data->runWS();

        $nilai = array();
        foreach($resy['data'] as $item){
                $mk = MataKuliah::where('id_matkul',$item['id_matkul'])->limit(1)->get();
                if(count($mk)>0){
                    $mk=$mk[0]->kode_mata_kuliah;
                }else{
                    $mk = "";
                }
                # add kode MK
                $item = Arr::add($item, 'kode_mata_kuliah', $mk);
                array_push($nilai,$item);
        }
    
        #filter history nilai sesuai krs saja
        $sesuaikrs=array();
        $act = "GetKRSMahasiswa";
        $limit = "";
        $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."' and id_periode='".$id_periode."'";
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $ress = $data->runWS(); 
        
        $sks_sem = 0;
        $tot_nil_index = 0;
        foreach ($nilai as $key => $value) {            
            foreach ($ress['data'] as $k => $val) {
                if($value['id_kelas']==$val['id_kelas']){
                    $tot_nil_index = $tot_nil_index + (doubleval($value['nilai_indeks']*doubleval($value['sks_mata_kuliah'])));
                    $sks_sem = $sks_sem + (doubleval($value['sks_mata_kuliah']));
                    array_push($sesuaikrs,$value);
                }
            }
        }
        if((int)$sks_sem==0){
            $ips = "0.00";
        }else{
            $ips = number_format(doubleval($tot_nil_index)/doubleval($sks_sem),2);
        }

        $biaya = ValidasiKRS::where('id_semester', '=', "".$id_periode."")
        ->where('id_registrasi_mahasiswa', '=',"".$id_reg_mhs."")
        ->first();

        if($biaya!=null){
            $biaya = $biaya->nominal_tagihan;
        }

        $global = self::getGlobalInfo($id_mhs,$id_reg_mhs,
        # $id_periode,
        '');

        $modelKHS = array();
        $modelKHS = Arr::add($modelKHS, 'list_mata_kuliah', $sesuaikrs);
        $modelKHS = Arr::add($modelKHS, 'id_periode', $id_periode);
        $modelKHS = Arr::add($modelKHS, 'ips', $ips);
        $modelKHS = Arr::add($modelKHS, 'sks_sem', (int)$sks_sem);
        $modelKHS = Arr::add($modelKHS, 'biaya_semester', $biaya);
        $modelKHS = Arr::add($modelKHS, 'ipk', $global['ipk']);
        $modelKHS = Arr::add($modelKHS, 'sks_ditempuh', (int)$global['sks_ditempuh']);
        $modelKHS = Arr::add($modelKHS, 'mk_underc', $global['mk_underc']);
        
        return $modelKHS;
    }

    #refactored code
    public static function getKHSForRAKM($id_mhs,$id_reg_mhs,$sms_awal,$sms_akhir)
    {                  
        $act = "GetRiwayatNilaiMahasiswa";
        $limit = "";
        $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."' and id_periode='".$sms_akhir."'";
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $resy = $data->runWS();

        $nilai = array();
        foreach($resy['data'] as $item){
                $mk = MataKuliah::where('id_matkul',$item['id_matkul'])->limit(1)->get();
                if(count($mk)>0){
                    $mk=$mk[0]->kode_mata_kuliah;
                }else{
                    $mk = "";
                }
                # add kode MK
                $item = Arr::add($item, 'kode_mata_kuliah', $mk);
                array_push($nilai,$item);
        }
    
        #filter history nilai sesuai krs saja
        $sesuaikrs=array();
        $act = "GetKRSMahasiswa";
        $limit = "";
        $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."' and id_periode='".$sms_akhir."'";
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $ress = $data->runWS(); 
        
        $sks_sem = 0;
        $tot_nil_index = 0;
        foreach ($nilai as $key => $value) {            
            foreach ($ress['data'] as $k => $val) {
                if($value['id_kelas']==$val['id_kelas']){
                    $tot_nil_index = $tot_nil_index + (doubleval($value['nilai_indeks']*doubleval($value['sks_mata_kuliah'])));
                    $sks_sem = $sks_sem + (doubleval($value['sks_mata_kuliah']));
                    array_push($sesuaikrs,$value);
                }
            }
        }
        if((int)$sks_sem==0){
            $ips = "0.00";
        }else{
            $ips = number_format(doubleval($tot_nil_index)/doubleval($sks_sem),2);
        }
        $biaya = ValidasiKRS::where('id_semester', '=', "".$sms_akhir."")
        ->where('id_registrasi_mahasiswa', '=',"".$id_reg_mhs."")
        ->first();
        $id_pembiayaan = null;

        if(empty($biaya)){
            $biaya = null;
            $id_pembiayaan = null;
        }else{
            $id_pembiayaan = $biaya->id_pembiayaan;
            $biaya = $biaya->nominal_tagihan;
        }

        $global = self::getGlobalInfoForRAKM($id_mhs,$id_reg_mhs,
        $sms_awal,$sms_akhir
        );

        $modelKHS = array();
        $modelKHS = Arr::add($modelKHS, 'list_mata_kuliah', $sesuaikrs);
        $modelKHS = Arr::add($modelKHS, 'id_periode', $sms_akhir);
        $modelKHS = Arr::add($modelKHS, 'ips', $ips);
        $modelKHS = Arr::add($modelKHS, 'sks_sem', (int)$sks_sem);
        $modelKHS = Arr::add($modelKHS, 'biaya_semester', $biaya);
        $modelKHS = Arr::add($modelKHS, 'id_pembiayaan', $id_pembiayaan);
        $modelKHS = Arr::add($modelKHS, 'ipk', $global['ipk']);
        $modelKHS = Arr::add($modelKHS, 'sks_ditempuh', (int)$global['sks_ditempuh']);
        $modelKHS = Arr::add($modelKHS, 'mk_underc', $global['mk_underc']);
        
        return $modelKHS;
    }

    #refactored code
    public static function getKRS($id_reg_mhs,$id_periode)
    {
        $datas['total_sks']=0;
        $datas['total_kelas']=0;
        $datas['result']=null;

        $act = "GetKRSMahasiswa";        
        $limit = "";
        $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."' and id_periode='".$id_periode."'";        
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $ress = $data->runWS(); 

        if($ress['data']){
            foreach ($ress['data'] as $key => $value) {
                $datas['total_sks']=$datas['total_sks']+doubleval($value['sks_mata_kuliah']);
            }
            $datas['total_kelas']=count($ress['data']);
        }

        $datas['result']=$ress['data'];

        return $datas;
    }

    #refactored code
    public static function getGlobalInfo($id_mhs,$id_reg_mhs,$id_periode)
    {
        $niltra = null;
        $nim_tra = ProfilMhsHelper::getNimTransfer($id_mhs,$id_reg_mhs);
        self::initHistoryNilai($id_reg_mhs,"");
    
        $hisNilai = HistoryNilai::where('id_registrasi_mahasiswa','=',$id_reg_mhs)->first();
        $hisNilai = json_decode($hisNilai->history_nilai,true);
        $result = $hisNilai;

        #filter history nilai sesuai krs saja
        $sesuaikrs=array();
        $act = "GetKRSMahasiswa";
        $limit = "";
        $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."'";
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $ress = $data->runWS(); 

        foreach ($result as $key => $value) {            
            foreach ($ress['data'] as $k => $val) {
                if($value['id_kelas']==$val['id_kelas']){
                    array_push($sesuaikrs,$value);
                }
            }
        }

        # jika ada nilai transfer maka push nilai transfer ke dalam $result yang telah dicari sebelumnya
        if(count($nim_tra)!=0){
            $niltra=ProfilMhsHelper::getNilaiTransfer($nim_tra[0]['id_registrasi_mahasiswa']);
            foreach($niltra as $e => $val)
            {
                $temp = array();
                $temp = Arr::add($temp, 'id_periode',"Transfer");
                $temp = Arr::add($temp, 'id_matkul',$val['id_matkul']);
                $temp = Arr::add($temp, 'kode_mata_kuliah',$val['kode_matkul_diakui']);
                $temp = Arr::add($temp, 'nama_mata_kuliah',$val['nama_mata_kuliah_diakui']);
                $temp = Arr::add($temp, 'sks_mata_kuliah',$val['sks_mata_kuliah_diakui']);
                $temp = Arr::add($temp, 'nilai_angka',"-");
                $temp = Arr::add($temp, 'nilai_huruf',$val['nilai_huruf_diakui']);
                $temp = Arr::add($temp, 'nilai_indeks',$val['nilai_angka_diakui']);
                array_push($sesuaikrs,$temp); 
            }
        }
        $groupbymk = array();
        foreach($sesuaikrs as $entry => $vals)
        {
            $groupbymk[$vals['nama_mata_kuliah']][]=$vals;
        }
        $nonduplicate=array();
        foreach($groupbymk as $in => $items){
            $last_nilai=0;
            $temp_choosen=array();
            foreach($items as $i => $it){
                if($it['nilai_angka']>=$last_nilai){
                    $last_nilai =$it['nilai_angka'];
                    $temp_choosen[0]=$it;                    
                }else if(!is_numeric($it['nilai_angka'])){
                    $temp_choosen[0]=$it;                    
                }
            }
            array_push($nonduplicate,$temp_choosen[0]);
        }


        $res['ipk'] = 0;         
        $res['mk_underc'] = 0;         
        $res['sks_ditempuh'] = 0;
        $totsks = 0;
        $totsksni = 0;
        foreach($nonduplicate as $k => $val)
        {
            if($val['nilai_huruf']=="E"||$val['nilai_huruf']=="D"){
                $res['mk_underc'] = $res['mk_underc']+1;
            }
            $res['sks_ditempuh'] = $res['sks_ditempuh']+$val['sks_mata_kuliah'];
            $totsks = $totsks + $val['sks_mata_kuliah'];
            $totsksni = $totsksni + ($val['nilai_indeks']*$val['sks_mata_kuliah']);
        }
        $res['ipk'] = $totsks == 0 ? 0 :round($totsksni/$totsks,2);

        $return['sks_ditempuh']=$res['sks_ditempuh'];
        $return['ipk']=$res['ipk'];
        $return['mk_underc']=$res['mk_underc'];

        return $return;
    }

    #refactored code
    public static function getGlobalInfoForRAKM($id_mhs,$id_reg_mhs,$smt_awal,$smt_akhir)
    {
        $niltra = null;
        $nim_tra = ProfilMhsHelper::getNimTransfer($id_mhs,$id_reg_mhs);
        
        self::initHistoryNilai($id_reg_mhs,"");

        # Mencari filter semester awal dan semester akhir untuk limit Review AKM
        $periode = array();

        for($i=substr(strval($smt_awal),0,-1);$i<=substr(strval($smt_akhir),0,-1);$i++){
            if($smt_awal==$smt_akhir){
                array_push($periode,$smt_akhir);
            }else if($i==substr(strval($smt_akhir),0,-1)){
                if(substr(strval($smt_akhir),-1)=="1"){
                    array_push($periode,$i."1");
                }else{
                    array_push($periode,$i."1",$i."2");
                }
            }else{
                if(substr(strval($smt_awal),-1)=="1"){
                    array_push($periode,$i."1",$i."2");
                }else{
                    array_push($periode,$i."2");
                }
            }
        }
    
        $hisNilai = HistoryNilai::where('id_registrasi_mahasiswa','=',$id_reg_mhs)->first();
        $hisNilai = json_decode($hisNilai->history_nilai,true);
        $result = array();

        foreach($hisNilai as $key => $value){
            # Iterate over each search condition
            foreach ($periode as $k => $v)
            {
                # If the array element does not meet the search condition then continue to the next element
                if ($value['id_periode']!= $v)
                {
                }else{
                    $mk = MataKuliah::where('id_matkul',$value['id_matkul'])->limit(1)->get();
                    if(count($mk)>0){
                        $mk=$mk[0]->kode_mata_kuliah;
                    }else{
                        $mk = "";
                    }
                    # add kode MK
                    $value = Arr::add($value, 'kode_mata_kuliah', $mk);
                    array_push($result,$value); 
                }
            }
        }

        #filter history nilai sesuai krs saja
        $sesuaikrs=array();
        $act = "GetKRSMahasiswa";
        $limit = "";
        $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."'";
        $record = "";
        $key = "";
        $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
        $ress = $data->runWS(); 

        foreach ($result as $key => $value) {            
            foreach ($ress['data'] as $k => $val) {
                if($value['id_kelas']==$val['id_kelas']){
                    array_push($sesuaikrs,$value);
                }
            }
        }
        # jika ada nilai transfer maka push nilai transfer ke dalam $result yang telah dicari sebelumnya
        if(count($nim_tra)!=0){            
            $niltra=ProfilMhsHelper::getNilaiTransfer($nim_tra[0]['id_registrasi_mahasiswa']);
            foreach($niltra as $e => $val)
            {
                $temp = array();
                $temp = Arr::add($temp, 'id_periode',"Transfer");
                $temp = Arr::add($temp, 'id_matkul',$val['id_matkul']);
                $temp = Arr::add($temp, 'kode_mata_kuliah',$val['kode_matkul_diakui']);
                $temp = Arr::add($temp, 'nama_mata_kuliah',$val['nama_mata_kuliah_diakui']);
                $temp = Arr::add($temp, 'sks_mata_kuliah',$val['sks_mata_kuliah_diakui']);
                $temp = Arr::add($temp, 'nilai_angka',"-");
                $temp = Arr::add($temp, 'nilai_huruf',$val['nilai_huruf_diakui']);
                $temp = Arr::add($temp, 'nilai_indeks',$val['nilai_angka_diakui']);
                array_push($sesuaikrs,$temp); 
            }
        }
        $groupbymk = array();
        foreach($sesuaikrs as $entry => $vals)
        {
            $groupbymk[$vals['nama_mata_kuliah']][]=$vals;
        }
        $nonduplicate=array();
    
        foreach($groupbymk as $in => $items){
            $last_nilai=0;
            $temp_choosen=array();
            foreach($items as $i => $it){
                if($it['nilai_angka']>=$last_nilai){
                    $last_nilai =$it['nilai_angka'];
                    $temp_choosen[0]=$it;                    
                }else if(!is_numeric($it['nilai_angka'])){
                    $temp_choosen[0]=$it;                    
                }
            }
            array_push($nonduplicate,$temp_choosen[0]);
        }


        $res['ipk'] = 0;         
        $res['mk_underc'] = 0;         
        $res['sks_ditempuh'] = 0;
        $totsks = 0;
        $totsksni = 0;
        foreach($nonduplicate as $k => $val)
        {
            if($val['nilai_huruf']=="E"||$val['nilai_huruf']=="D"){
                $res['mk_underc'] = $res['mk_underc']+1;
            }
            $res['sks_ditempuh'] = $res['sks_ditempuh']+$val['sks_mata_kuliah'];
            $totsks = $totsks + $val['sks_mata_kuliah'];
            $totsksni = $totsksni + ($val['nilai_indeks']*$val['sks_mata_kuliah']);
        }
        $res['ipk'] = $totsks == 0 ? 0 :round($totsksni/$totsks,2);

        $return['sks_ditempuh']=$res['sks_ditempuh'];
        $return['ipk']=$res['ipk'];
        $return['mk_underc']=$res['mk_underc'];

        return $return;
    }

    #refactored code
    public static function clearTempData($user){
        $hisNilai = HistoryNilai::where('id_registrasi_mahasiswa','=',$user->id_registrasi_mahasiswa);
        $hisNilai->delete();
    
        $tra = TranskripNilai::where('id_registrasi_mahasiswa','=',json_decode($user->data_mahasiswa,true)['id_registrasi_mahasiswa']);
        $tra->delete();
    }

    #refactored code
    public static function getNonDuplicateTranskrip($user,$nim_tra,$req_smt_awal,$req_smt_akhir)
    {
        $smt_awal = "";
        $smt_akhir = "";
        $periode = array();
        if($req_smt_awal!="x" || $req_smt_akhir!="x"){
            $smt_awal = $req_smt_awal;
            $smt_akhir = $req_smt_akhir;
        }

        for($i=substr(strval($smt_awal),0,-1);$i<=substr(strval($smt_akhir),0,-1);$i++){
            if($smt_awal==$smt_akhir){
                array_push($periode,$smt_akhir);
            }else if($i==substr(strval($smt_akhir),0,-1)){
                if(substr(strval($smt_akhir),-1)=="1"){
                    array_push($periode,$i."1");
                }else{
                    array_push($periode,$i."1",$i."2");
                }
            }else{
                if(substr(strval($smt_awal),-1)=="1"){
                    array_push($periode,$i."1",$i."2");
                }else{
                    array_push($periode,$i."2");
                }
            }
        }

        $hisNilai = HistoryNilai::where('id_registrasi_mahasiswa','=',$user->id_registrasi_mahasiswa)->first();
        $hisNilai = json_decode($hisNilai->history_nilai,true);
        $result = array();
        
        foreach($hisNilai as $key => $value){
            // Iterate over each search condition
            foreach ($periode as $k => $v)
            {
                // If the array element does not meet the search condition then continue to the next element
                if ($value['id_periode']!= $v)
                {
                }else{
                    $mk = MataKuliah::where('id_matkul',$value['id_matkul'])->limit(1)->get();
                    if(count($mk)>0){
                        $mk=$mk[0]->kode_mata_kuliah;
                    }else{
                        $mk = "";
                    }
                    // add kode MK
                    $value = Arr::add($value, 'kode_mata_kuliah', $mk);
                    array_push($result,$value); 
                }
            }
        }

        //filter history nilai sesuai krs saja
        $sesuaikrs=array();
        $ress = ProfilMhsHelper::getKRSMahasiswa($user->id_registrasi_mahasiswa,"");

        foreach ($result as $key => $value) {            
            foreach ($ress as $k => $val) {
                if($value['id_kelas']==$val['id_kelas']){
                    array_push($sesuaikrs,$value);
                }
            }
        }

        // jika ada nilai transfer maka push nilai transfer ke dalam $sesuaikrs yang telah dicari sebelumnya
        if(count($nim_tra)!=0){
            $niltra=ProfilMhsHelper::getNilaiTransfer($nim_tra[0]['id_registrasi_mahasiswa']);
            foreach($niltra as $e => $val)
            {
                $temp = array();
                $temp = Arr::add($temp, 'id_periode',"Transfer");
                $temp = Arr::add($temp, 'id_matkul',$val['id_matkul']);
                $temp = Arr::add($temp, 'kode_mata_kuliah',$val['kode_matkul_diakui']);
                $temp = Arr::add($temp, 'nama_mata_kuliah',$val['nama_mata_kuliah_diakui']);
                $temp = Arr::add($temp, 'sks_mata_kuliah',$val['sks_mata_kuliah_diakui']);
                $temp = Arr::add($temp, 'nilai_angka',"-");
                $temp = Arr::add($temp, 'nilai_huruf',$val['nilai_huruf_diakui']);
                $temp = Arr::add($temp, 'nilai_indeks',$val['nilai_angka_diakui']);
                array_push($sesuaikrs,$temp); 
            }
        }

        foreach($sesuaikrs as $entry => $vals)
        {
            $groupbymk[$vals['nama_mata_kuliah']][]=$vals;
        }
        $nonduplicate=array();
        foreach($groupbymk as $in => $items){
            $last_nilai=0;
            $temp_choosen=array();
            foreach($items as $i => $it){
                if($it['nilai_angka']>=$last_nilai){
                    $last_nilai =$it['nilai_angka'];
                    $temp_choosen[0]=$it;                    
                }else if(!is_numeric($it['nilai_angka'])){
                    $temp_choosen[0]=$it;                    
                }
            }
            array_push($nonduplicate,$temp_choosen[0]);
        }

        $sort = array();
        foreach($nonduplicate as $k=>$v) {
        $sort['id_periode'][$k] = $v['id_periode'];
        }
        # sort by event_type desc and then title asc
        array_multisort($sort['id_periode'], SORT_ASC,$nonduplicate);

        return $nonduplicate;
    }

    #refactored code
    public static function getNonDuplicateTranskripWithout_ids($user,$nim_tra,$req_smt_awal,$req_smt_akhir,$without_ids)
    {
        $smt_awal = "";
        $smt_akhir = "";
        $periode = array();
        if($req_smt_awal!="x" || $req_smt_akhir!="x"){
            $smt_awal = $req_smt_awal;
            $smt_akhir = $req_smt_akhir;
        }

        for($i=substr(strval($smt_awal),0,-1);$i<=substr(strval($smt_akhir),0,-1);$i++){
            if($smt_awal==$smt_akhir){
                array_push($periode,$smt_akhir);
            }else if($i==substr(strval($smt_akhir),0,-1)){
                if(substr(strval($smt_akhir),-1)=="1"){
                    array_push($periode,$i."1");
                }else{
                    array_push($periode,$i."1",$i."2");
                }
            }else{
                if(substr(strval($smt_awal),-1)=="1"){
                    array_push($periode,$i."1",$i."2");
                }else{
                    array_push($periode,$i."2");
                }
            }
        }

        $hisNilai = HistoryNilai::where('id_registrasi_mahasiswa','=',$user->id_registrasi_mahasiswa)->first();
        $hisNilai = json_decode($hisNilai->history_nilai,true);
        $result = array();
        
        foreach($hisNilai as $key => $value){
            // Iterate over each search condition
            foreach ($periode as $k => $v)
            {
                // If the array element does not meet the search condition then continue to the next element
                if ($value['id_periode']!= $v)
                {
                }else{
                    $mk = MataKuliah::where('id_matkul',$value['id_matkul'])->limit(1)->get();
                    if(count($mk)>0){
                        $mk=$mk[0]->kode_mata_kuliah;
                    }else{
                        $mk = "";
                    }
                    // add kode MK
                    $value = Arr::add($value, 'kode_mata_kuliah', $mk);
                    array_push($result,$value); 
                }
            }
        }

        //filter history nilai sesuai krs saja
        $sesuaikrs=array();
        $ress = ProfilMhsHelper::getKRSMahasiswa($user->id_registrasi_mahasiswa,"");

        foreach ($result as $key => $value) {            
            foreach ($ress as $k => $val) {
                if($value['id_kelas']==$val['id_kelas']){
                    array_push($sesuaikrs,$value);
                }
            }
        }

        // jika ada nilai transfer maka push nilai transfer ke dalam $sesuaikrs yang telah dicari sebelumnya
        if(count($nim_tra)!=0){
            $niltra=ProfilMhsHelper::getNilaiTransfer($nim_tra[0]['id_registrasi_mahasiswa']);
            foreach($niltra as $e => $val)
            {
                $temp = array();
                $temp = Arr::add($temp, 'id_periode',"Transfer");
                $temp = Arr::add($temp, 'id_matkul',$val['id_matkul']);
                $temp = Arr::add($temp, 'kode_mata_kuliah',$val['kode_matkul_diakui']);
                $temp = Arr::add($temp, 'nama_mata_kuliah',$val['nama_mata_kuliah_diakui']);
                $temp = Arr::add($temp, 'sks_mata_kuliah',$val['sks_mata_kuliah_diakui']);
                $temp = Arr::add($temp, 'nilai_angka',"-");
                $temp = Arr::add($temp, 'nilai_huruf',$val['nilai_huruf_diakui']);
                $temp = Arr::add($temp, 'nilai_indeks',$val['nilai_angka_diakui']);
                array_push($sesuaikrs,$temp); 
            }
        }
        foreach($sesuaikrs as $entry => $vals)
        {
            $groupbymk[$vals['nama_mata_kuliah']][]=$vals;
        }
        $nonduplicate=array();        
        foreach($groupbymk as $in => $items){
            $last_nilai=0;
            $temp_choosen=array();
            foreach($items as $i => $it){
                if($it['nilai_angka']>=$last_nilai){
                    $last_nilai =$it['nilai_angka'];
                    $temp_choosen[0]=$it;                    
                }else if(!is_numeric($it['nilai_angka'])){
                    $temp_choosen[0]=$it;                    
                }
            }
            array_push($nonduplicate,$temp_choosen[0]);
        }

        //Filter yang tidak terchecklist
        foreach ($nonduplicate as $key => $ob) {
            foreach($without_ids as $k => $val){
                if ($ob['nama_mata_kuliah'] == $val->nama && $ob['id_periode'] == $val->id_periode) {
                   unset($nonduplicate[$key]);
                }
            }
         }

        $sort = array();
        foreach($nonduplicate as $k=>$v) {
        $sort['id_periode'][$k] = $v['id_periode'];
        }
        # sort by event_type desc and then title asc
        array_multisort($sort['id_periode'], SORT_ASC,$nonduplicate);

        return $nonduplicate;
    }
}