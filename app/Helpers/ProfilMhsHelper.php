<?php

namespace App\Helpers;
use App\Models\HistoryNilai;
use App\Models\TranskripNilai;
use App\Models\MataKuliah;
use App\Models\BiodataMahasiswa;
use Sentinel;
use App\Services\FeederDiktiApiService;
use App\Models\ValidasiKRS;

use Illuminate\Support\Arr;


class ProfilMhsHelper{

  #refactored code
  public static function getBio($user)
  {
    $id_mhs = json_decode($user->data_mahasiswa,true)['id_mahasiswa'];
    $act = "GetBiodataMahasiswa";
    $limit = "";
    $filter = "id_mahasiswa = '".$id_mhs."'";
    $record="";
    $key = "";
    $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);

    $bio = BiodataMahasiswa::where('id_registrasi_mahasiswa','=',$user->id_registrasi_mahasiswa)->first();
    try {
      if ($bio!=null) {
      } else {
        $res = $data->runWS(); 
        $bioM = new BiodataMahasiswa();
        $bioM->id_registrasi_mahasiswa = $user->id_registrasi_mahasiswa;
        $bioM->biodata = json_encode($res['data']);
        $bioM->save();
        $bio = BiodataMahasiswa::where('id_registrasi_mahasiswa','=',$user->id_registrasi_mahasiswa)->first();
      }          
    } catch (\Exception $e) {
      $res['error']=true;
      $res['message']=$e->getMessage();
    }

    return json_decode($bio->biodata,true)[0];
  }
  
  public static function getListAnggotaAktivitasMahasiswa($user,$peran)
  {    
    $id_reg_mhs = json_decode($user->data_mahasiswa,true)['id_registrasi_mahasiswa'];
    $act = "GetListAnggotaAktivitasMahasiswa";
    $limit = "";
    $filter = "id_registrasi_mahasiswa = '".$id_reg_mhs."' and nama_jenis_peran = '".$peran."'";
    $record="";
    $key = "";
    $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);

    try {
        $res = $data->runWS();         
    } catch (\Exception $e) {
      $res['error']=true;
      $res['message']=$e->getMessage();
    }

    // return json_decode($bio->biodata,true)[0];
    return ($res['data']);
  }

  #refactored code
  public static function getNimTransfer($id_mhs,$id_registrasi_mahasiswa)
  {
    $his = self::getRiwayatPendidikan($id_mhs,$id_registrasi_mahasiswa);
    $nim = array();
    $nim_tra = array();
    foreach($his as $hi)
    {
      $temp = array();
      $temp['nim'] = $hi['nim']; 
      $temp['id_registrasi_mahasiswa'] = $hi['id_registrasi_mahasiswa']; 
      array_push($nim,$temp);
    }

    foreach ($nim as $item){
      $act = "GetNilaiTransferPendidikanMahasiswa";
      $limit = "1";
      $filter = "id_registrasi_mahasiswa = '".$item['id_registrasi_mahasiswa']."'";
      $record = "";
      $key = "";
      $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
      $res = $data->runWS();    
      if(count($res['data'])!= 0){
          array_push($nim_tra,$item);
      }
    }

    return ($nim_tra);
  }

  #refactored code
  public static function getRiwayatPendidikan($id_mhs,$id_registrasi_mahasiswa)
  {      
    $act = "GetListRiwayatPendidikanMahasiswa";
    $limit = "";
    if($id_registrasi_mahasiswa==""){
      $filter = "id_mahasiswa = '".$id_mhs."'";
    }else{
      $filter = "id_mahasiswa = '".$id_mhs."' and id_registrasi_mahasiswa = '".$id_registrasi_mahasiswa."'";
    }
    $record = "";
    $key = "";
    $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
    $riwayat = $data->runWS();
    return $riwayat['data'];
  }

  #refactored code
  public static function getNilaiTransfer($id_registrasi_mahasiswa)
  {
      $act = "GetNilaiTransferPendidikanMahasiswa";
      $limit = "";
      $filter = "id_registrasi_mahasiswa = '".$id_registrasi_mahasiswa."'";
      $record = "";
      $key = "";
      $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
      $res = $data->runWS(); 

      return $res['data'];
  }

  #refactored code
  public static function getPeriodeAKM($id_registrasi_mahasiswa)
  {
    $act = "GetListPerkuliahanMahasiswa";
    $limit = "";
    $filter = "id_registrasi_mahasiswa = '".$id_registrasi_mahasiswa."'";
    $record = "";
    $key = "";
    $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
    $res = $data->runWS(); 

    $periode = array();
      foreach($res['data'] as $hi)
      {
          $periode[$hi['nama_semester']] = true;
      }
      $periode = array_keys($periode);
      sort($periode);
    return $periode;
  }

  #refactored code
  public static function getKRSMahasiswa($id_registrasi_mahasiswa,$periode)
  {
      $act = "GetKRSMahasiswa";
      $limit = "";
      if($periode==""){
        $filter = "id_registrasi_mahasiswa = '".$id_registrasi_mahasiswa."'";
      }else{
        $filter = "id_registrasi_mahasiswa = '".$id_registrasi_mahasiswa."' and id_periode='".$periode."'";
      }
      $record = "";
      $key = "";
      $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
      $res = $data->runWS(); 
      return $res['data'];
  }

  #refactored code
  public static function getWilayahMahasiswa($bio)
  {
      $act = "GetWilayah";
      $limit = "";
      $filter = "id_wilayah = '".substr($bio['id_wilayah'],0,-4)."0000' or id_wilayah = '".substr($bio['id_wilayah'],0,-2)."00'";

      $record = "";
      $key = "";
      $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
      $wil = $data->runWS();    
      $res['prov'] = "";
      $res['reg'] = "";
      foreach($wil['data'] as $w){
        if(substr($w['nama_wilayah'],0,1)=="P"){
          $res['prov'] = $w['nama_wilayah'];
        }else{
          $res['reg'] = $w['nama_wilayah'];
        }
      }
      return $res;
  }

  #refactored code
  public static function getRekamKuliah($id_registrasi_mahasiswa)
  {
    $act = "GetAktivitasKuliahMahasiswa";
    $limit = "";
    $filter = "id_registrasi_mahasiswa = '".$id_registrasi_mahasiswa."'";
    $record = "";
    $key = "";
    $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
    $res = $data->runWS(); 
    return $res['data'];
  }

  #refactored code
  public static function getRekamKuliahAllYear($id_semester,$id_prodis)
  {
    $id_prodi = "";
    $fil = "";
    if($id_prodis == null){
      $fil = "id_semester ='".$id_semester."'";
    }else{
      foreach ($id_prodis as $key => $value) {
        $id_prodi = $id_prodi."'".$value."',"; 
      }
      $id_prodi = rtrim($id_prodi, ",");
      $fil = "id_semester ='".$id_semester."' and id_prodi in (".$id_prodi.")";
    }

    $act = "GetAktivitasKuliahMahasiswa";
    $limit = "";
    $filter = $fil;
    $record = "";
    $key = "";
    $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
    $res = $data->runWS(); 
    return $res['data'];
  }

  #refactored code
  public static function getPrestasiMahasiswa($bio)
  {
      $act = "GetListPrestasiMahasiswa";
      $limit = "";
      $filter = "id_mahasiswa = '".$bio['id_mahasiswa']."'";

      $record = "";
      $key = "";
      $data = new FeederDiktiApiService($act,$limit,$filter,$record,$key);
      $res = $data->runWS();  
      return $res['data'];
  }
}