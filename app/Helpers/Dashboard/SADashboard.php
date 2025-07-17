<?php

namespace App\Helpers\Dashboard;

use App\Models\Users;
use App\Helpers\AKMHelper;
use App\Helpers\customFormat;


class SADashboard{
  public static function getDatasetChartAKMSA($id_periode,$prodi){
    $all_mhs = array();
    $akm_prodi = array();

    $unique_status_for_all = array();
    $DAKALL = AKMHelper::exportDataAktivitasKuliahALL($id_periode);
    foreach ($DAKALL as $k => $val_dak) {
      array_push($unique_status_for_all,$val_dak['nama_status_mahasiswa']);
    }

    foreach ($prodi as $key => $value) {
        set_time_limit(60);
        $sts_at_prodi = AKMHelper::exportDataAktivitasKuliah($value->id_prodi,$id_periode);
        array_push($all_mhs,$sts_at_prodi);

        $type_status = array();
        foreach ($sts_at_prodi as $key => $status) {
          array_push($type_status,$status['nama_status_mahasiswa']);
        }

        $mhs_prodi = Users::where('id_prodi', $value->id_prodi)->where('status_mahasiswa', "AKTIF")->where("id_periode","<=",$id_periode)->get();
        $blm_akm = count($mhs_prodi)-count($type_status);
        for ($i=0; $i < $blm_akm ; $i++) { 
          array_push($type_status,"Belum AKM");
        }
        $type_status = array_count_values($type_status);
        
        # set 0 to status akm yg blm ada
        array_push($unique_status_for_all,"Belum AKM");
        $arr = array_unique($unique_status_for_all);
        $arrs = array_diff(array_values($arr),array_keys($type_status));

        $ttt=array();
        array_push($ttt,$type_status);
        foreach ($arrs as $keys => $valuess) {
          array_push($ttt,array($valuess=>0));
        }
        
        $temp_prodi = array();
        $temp_prodi['id_prodi'] = $value->id_prodi;
        $temp_prodi['nama_prodi'] = $value->nama_program_studi;
        foreach ($ttt as $kunci => $isi) {
          foreach ($isi as $kc => $isis) {
            $temp_prodi['status_akm'][$kc] = $isis;
          }
        }                 
      array_push($akm_prodi,$temp_prodi);
    }      
    
    $data = array();
    foreach ($akm_prodi[0]['status_akm'] as $key => $value) {
      $data[$key] = array();
    }
    foreach ($akm_prodi as $key => $value) {
      foreach ($value['status_akm'] as $keys => $values) {
        array_push($data[$keys],$values);
      }
    }
    $dataset_data = array();
    foreach ($akm_prodi[0]['status_akm'] as $key => $value) {
      $tem = array();
      $tem["label"] = $key;
      $tem["data"] = $data[$key];
      if($key == "Aktif"){
        $tem["stack"] = 'Stack 0';
      }else{
        $tem["stack"] = 'Stack 1';
      }
      switch ($key) {
        case 'Aktif':
          $tem["backgroundColor"] = "rgb(0,100,0)";
          break;
        case 'Menunggu Uji Kompetensi':
          $tem["backgroundColor"] = "rgba(0, 97, 242, 1)";
          break;            
        case 'Cuti':
          $tem["backgroundColor"] = "rgb(255,215,0)";
          break;
        case 'Non-Aktif':
          $tem["backgroundColor"] = "rgb(255,165,0)";
          break;
        case 'Belum AKM':
          $tem["backgroundColor"] = "rgb(139,0,0)";
          break;            
        default:
          $tem["backgroundColor"] = "rgba(255,0,0,0.6)";
          break;
      }
      array_push($dataset_data,$tem);
    }
    return $dataset_data;
  }
  
}