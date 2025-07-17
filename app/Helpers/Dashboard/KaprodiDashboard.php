<?php

namespace App\Helpers\Dashboard;

use App\Models\Users;
use App\Helpers\AKMHelper;
use App\Helpers\customFormat;
use App\Helpers\LulusanHelper;


class KaprodiDashboard{
  
  public static function getDatasetChartAKMKA($id_periode,$prodi,$nama_angkatan,$id_reg_mhs){
    $all_mhs = array();
    $akm_prodi_ang = array();

    $id_prodi = $prodi[0]['id_prodi'];

    $unique_status_for_all = array(); #untuk menyimpan status unique dalam satu prodi pada suatu periode
    #DAKLL untuk menyimpan collection AKM dalam satu prodi pada suatu periode
    $DAKALL = AKMHelper::exportDataAktivitasKuliah($id_prodi,$id_periode);
    $status_mhs = Users::distinct('status_mahasiswa')
    ->where('status_mahasiswa','!=',"")            
    ->pluck('status_mahasiswa');

    #menambah attribut angkatan pada array DAKLL
    foreach ($DAKALL as $k => $val_dak) {
      try {
        //code...
        $DAKALL[$k]['angkatan'] = substr(
          Users::where('id_registrasi_mahasiswa',$val_dak['id_registrasi_mahasiswa'])
          ->first()->id_periode,0,4);
      } catch (\Throwable $th) {
        $DAKALL[$k]['angkatan'] = substr($val_dak['nim'],0,4);
      }
    }
    
    #mengambil status unique dari $DAKALL ke $unique_status_for_all
    foreach ($DAKALL as $k => $val_dak) {
      array_push($unique_status_for_all,$val_dak['nama_status_mahasiswa']);
    }
    

    foreach ($nama_angkatan as $key => $value) {
        set_time_limit(60);
        #cari DAKALL sesuai angkatan $value
        $sts_at_angkatan = array_filter($DAKALL, function ($var) use(&$value) {
          return ($var['angkatan'] == $value);
        }); 
        array_push($all_mhs,$sts_at_angkatan);

        $type_status = array();
        foreach ($sts_at_angkatan as $key => $status) {
          array_push($type_status,$status['nama_status_mahasiswa']);
        }
        $mhs_prodi_ang = Users::where('id_prodi', $id_prodi)
        ->where("id_periode","LIKE",$value.'%')
        ->where(function ($query) use($id_reg_mhs) {
          $query
          ->whereIn('id_registrasi_mahasiswa', $id_reg_mhs)
          ->orWhere('status_mahasiswa',"AKTIF");
        })
        ->get();
        
        $blm_akm = count($mhs_prodi_ang)-count($type_status);
        for ($i=0; $i < $blm_akm ; $i++) { 
          array_push($type_status,"Belum AKM");
        }
        $type_status = array_count_values($type_status);
        
        # set 0 to status akm yg blm ada
        array_push($unique_status_for_all,"Belum AKM");
        $arr = array_unique($unique_status_for_all);
        $arrs = array_diff(array_values($arr),array_keys($type_status));
        
        $status_n_value_ang=array();
        array_push($status_n_value_ang,$type_status);
        foreach ($arrs as $keys => $valuess) {
          array_push($status_n_value_ang,array($valuess=>0));
        }

        $temp_angkatan = array();
        $temp_angkatan['nama_angkatan'] = $value;
        foreach ($status_n_value_ang as $kunci => $isi) {
          foreach ($isi as $kc => $isis) {
            $temp_angkatan['status_akm'][$kc] = $isis;
          }
        }                 
      array_push($akm_prodi_ang,$temp_angkatan);
    }      
    
    $data = array();
    foreach ($akm_prodi_ang[0]['status_akm'] as $key => $value) {
      $data[$key] = array();
    }
    
    foreach ($akm_prodi_ang as $key => $value) {
      foreach ($value['status_akm'] as $keys => $values) {
        array_push($data[$keys],$values);
      }
    }
    
    $dataset_data = array();
    foreach ($akm_prodi_ang[0]['status_akm'] as $key => $value) {
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

  public static function getMhsAktifProdiPerAngkatan($id_prodi)
  {
      # Doughnout Graph =======================================================
      # cari nama angkatan dari prodi terkait pada tahun akademik sesuai filter
      $mhs_aktif = Users::where('id_prodi',$id_prodi)
      ->where('status_mahasiswa', 'AKTIF')
      ->get('id_periode')->unique('id_periode');  
      $nama_angkatan = customFormat::getPeriodeAngkatan($mhs_aktif);
      $nama_angkatan = array_values(array_unique($nama_angkatan));
         
      $isi_angkatan = array();
      foreach ($nama_angkatan as $key => $ang) {
        array_push(
          $isi_angkatan,
          count(
            Users::where('id_prodi',$id_prodi)
            ->where('status_mahasiswa', 'AKTIF')
            ->where('id_periode','LIKE', $ang.'%')->get()
            )
        );
      }

      foreach ($nama_angkatan as &$value) {
          $value = 'Angkatan ' . $value;
      }
      unset($value);
      
      return [$isi_angkatan,$nama_angkatan];
  }

  public static function getAKMProdiPerAngkatan($prodi,$id_prodi,$id_periode)
  {
      $mhs_aktif_pd_periode_filter = AKMHelper::exportDataAktivitasKuliah($id_prodi,$id_periode);

      $id_reg_mhs = array();
      $id_reg_mhs_aktif_pd_periode_filter = customFormat::getACertainAttrOfCollection(collect($mhs_aktif_pd_periode_filter),['id_registrasi_mahasiswa'],"array");
      foreach ($id_reg_mhs_aktif_pd_periode_filter as $key => $value) {
        array_push($id_reg_mhs,$value['id_registrasi_mahasiswa']);
      }

      #cari nama angkatan dari prodi terkait pada tahun akademik sesuai filter
      $mhs_aktif = Users::where('id_prodi',$id_prodi)
      ->whereIn('id_registrasi_mahasiswa', $id_reg_mhs)
      ->get('id_periode')->unique('id_periode');  
      $nama_angkatan = customFormat::getPeriodeAngkatan($mhs_aktif);
      $nama_angkatan = array_values(array_unique($nama_angkatan));
      $all_mhs_blm_akm_dalam_satu_angkatan = Users::where("status_mahasiswa","AKTIF")
      ->where('id_prodi',$id_prodi)
      ->where(function ($query) use($nama_angkatan) {
        foreach($nama_angkatan as $v){
          $query->where("id_periode",'NOT LIKE', $v.'%');
         }
      })
      ->where("id_periode",'<=',$id_periode."3")
      ->get();
      
      $nama_angkatan_addtn = customFormat::getPeriodeAngkatan($all_mhs_blm_akm_dalam_satu_angkatan);
      $nama_angkatan_addtn = array_values(array_unique($nama_angkatan_addtn));
      $nama_angkatan_all = array_merge($nama_angkatan,$nama_angkatan_addtn);
      sort($nama_angkatan_all);
      foreach ($all_mhs_blm_akm_dalam_satu_angkatan as $key => $value) {
        array_push($id_reg_mhs,$value['id_registrasi_mahasiswa']);
      }

      
      $dataset_data = KaprodiDashboard::getDatasetChartAKMKA($id_periode,$prodi,$nama_angkatan_all,$id_reg_mhs);
      
      foreach ($nama_angkatan_all as &$value) {
        $value = 'Angkatan ' . $value;
      }
      unset($value);

      return [$dataset_data,$nama_angkatan_all];
  }

  public static function getMhsLulusDo($id_prodi)
  {
    $all_mhs = array();

    $lulusdo = LulusanHelper::getListMahasiswaLulusDO($id_prodi);
    $tahun_lulusdo = customFormat::getACertainAttrOfCollection(collect($lulusdo),['tanggal_keluar'],"array");
    $tahun_lulusdo_unique = customFormat::getPeriodeTahunLulusan($tahun_lulusdo,'tanggal_keluar');
    $tahun_lulusdo_unique = array_values(array_unique($tahun_lulusdo_unique)); 
    sort($tahun_lulusdo_unique);

    $tahun_lulusdo_str = array();
    foreach ($tahun_lulusdo_unique as $key => $value) {
      array_push($tahun_lulusdo_str,"Tahun ".$value);
    }
    
    $status_for_all = array();
    foreach ($lulusdo as $k => $val_dak) {
      array_push($status_for_all,$val_dak['nama_jenis_keluar']);
    }
    $unique_status = array_values(array_unique($status_for_all));
    
    // dd($lulusdo);
    $status_final = array();
    foreach ($tahun_lulusdo_unique as $key => $value) {
        set_time_limit(60);
        
        $sts_at_angkatan = array_filter($lulusdo, function ($var) use(&$value) {
          return (substr($var['tanggal_keluar'],6,4) == $value);
        }); 
        array_push($all_mhs,$sts_at_angkatan);

        $type_status = array();
        foreach ($sts_at_angkatan as $key => $status) {
          array_push($type_status,$status['nama_jenis_keluar']);
        }

        $type_status = array_count_values($type_status);

        $arr = array_unique($status_for_all);
        $arrs = array_diff(array_values($arr),array_keys($type_status));
                
        $status_n_value_ang = array();
        array_push($status_n_value_ang,$type_status);
        foreach ($arrs as $keys => $valuess) {
          array_push($status_n_value_ang,array($valuess=>0));
        }

        $temp_tahun = array();
        $temp_tahun['nama_tahun'] = $value;
        foreach ($status_n_value_ang as $kunci => $isi) {
          foreach ($isi as $kc => $isis) {
            $temp_tahun['status_lulusdo'][$kc] = $isis;
          }
        }                 
        array_push($status_final,$temp_tahun);
      }      
  
    $data = array();
    foreach ($status_final[0]['status_lulusdo'] as $key => $value) {
      $data[$key] = array();
    }
    
    foreach ($status_final as $key => $value) {
      foreach ($value['status_lulusdo'] as $keys => $values) {
        array_push($data[$keys],$values);
      }
    }
    
    # get datasets;
    $dataset_data = array();
    foreach ($status_final[0]['status_lulusdo'] as $key => $value) {
      $tem = array();
      $tem["label"] = $key;
      $tem["data"] = $data[$key];
      if($key == "Lulus"){
        $tem["stack"] = 'Stack 0';
      }else{
        $tem["stack"] = 'Stack 1';
      }
      switch ($key) {
        case 'Lulus':
          $tem["backgroundColor"] = "rgb(0,100,0)";
          break;
        case 'Mengundurkan diri':
          $tem["backgroundColor"] = "rgba(0, 97, 242, 1)";
          break;            
        case 'Dikeluarkan':
          $tem["backgroundColor"] = "rgb(255,215,0)";
          break;
        case 'Wafat':
          $tem["backgroundColor"] = "rgb(255,165,0)";
          break;
        case 'Mutasi':
          $tem["backgroundColor"] = "rgb(139,0,0)";
          break;            
        default:
          $tem["backgroundColor"] = "rgba(255,0,0,0.6)";
          break;
      }
      array_push($dataset_data,$tem);
    }

    return [$tahun_lulusdo_unique,$tahun_lulusdo_str,$dataset_data];
  }

  public static function getLulusanProdiAtTahun($id_prodi,$tahun_lulus)
  {
    $lulusdo = LulusanHelper::getListMahasiswaLulusDO($id_prodi,"Lulus",$tahun_lulus);

    #cari angkatan dalam tahun lulus
    $all_angkatan = array();
    $unique_angkatan = array();
    $angkatan = customFormat::getACertainAttrOfCollection(collect($lulusdo),['angkatan'],'array');
    foreach ($angkatan as $key => $value) {
      array_push($all_angkatan,$value['angkatan']);
    }
    $unique_angkatan = array_unique($all_angkatan);
    sort($unique_angkatan);

    $unique_angkatan_str = array();
    foreach ($unique_angkatan as $key => $value) {
      array_push($unique_angkatan_str,"Angkatan ".$value);
    }

    $jumlah_perangkatan = array_count_values($all_angkatan);
    ksort($jumlah_perangkatan);

    $dataset_data = array();
    $temp = array();
    $tem["label"] = "Lulus";
    $tem["stack"] = "Stack 0";
    $tem["data"] = array_values($jumlah_perangkatan);
    $tem["backgroundColor"] = "rgb(0,100,0)";      
    array_push($dataset_data,$tem);

    return [$unique_angkatan_str,$dataset_data,$lulusdo,$unique_angkatan];
  }

  public static function getDataIPKDanMasaStudi($data_lulusan,$angkatan_lulusan)
  {
    $ipk_masa_study = array(); 

    $tem_ipk_all = 0;
    $tem_msstdy_all = 0;
    foreach ($angkatan_lulusan as $key => $value) {
      $tem = array();
      $tem['angkatan'] = $value;

      $mhs_baru_data = array_filter($data_lulusan, function ($var) use(&$value) {
        return ($var['angkatan'] == $value && $var['nm_jns_daftar']=="Peserta didik baru");
      });
      $mhs_baru = count($mhs_baru_data);
      $tem['mhs_baru'] = $mhs_baru;

      
      $mhs_pindahan_data = array_filter($data_lulusan, function ($var) use(&$value) {
        return ($var['angkatan'] == $value && $var['nm_jns_daftar']=="Pindahan");
      }); 
      $mhs_pindahan = count($mhs_pindahan_data);
      $tem['mhs_pindahan'] = $mhs_pindahan;

      $tem['total_mhs'] = $mhs_pindahan + $mhs_baru;

      $data_per_angkatan = array_merge($mhs_baru_data,$mhs_pindahan_data);
      
      $tem_ipk = 0;      
      $tem_msstdy = 0;
      foreach ($data_per_angkatan as $key => $value) {
        $tem_ipk = $tem_ipk + $value['ipk'];

        $awal = substr($value['nm_smt'],0,4).(substr($value['nm_smt'],10,(strlen($value['nm_smt'])-10))=="Ganjil"?1:2);        
        $akhir = $value['id_periode_keluar'];
        $full = 1;
        $half = .5;
        for ($i=substr($awal,0,4); $i < substr($akhir,0,4)+1 ; $i++) { 
          if($i==substr($awal,0,4)){
            if(substr($awal,4,1)==1){
              $tem_msstdy = $tem_msstdy +$full; 
            }else{
              $tem_msstdy = $tem_msstdy +$half; 
            }
          }else if($i==substr($akhir,0,4)){
            if(substr($akhir,4,1)==2){
              $tem_msstdy = $tem_msstdy +$full; 
            }else{
              $tem_msstdy = $tem_msstdy +$half; 
            }
          }else{
            $tem_msstdy = $tem_msstdy +$full; 
          }
        }
      }
      $tem['masa_studi'] = $tem_msstdy/count($data_per_angkatan);
      $tem['ipk'] = $tem_ipk/count($data_per_angkatan);
      array_push($ipk_masa_study,$tem);
      $tem_msstdy_all = $tem_msstdy_all + $tem_msstdy;
      $tem_ipk_all = $tem_ipk_all + $tem_ipk;
    }
    
    $resume['ipk'] = $tem_ipk_all/count($data_lulusan);
    $resume['masa_studi'] = $tem_msstdy_all/count($data_lulusan);
   
    return [$ipk_masa_study,$resume];
  }
}