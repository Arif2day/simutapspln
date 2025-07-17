<?php

namespace App\Helpers;

class CustomFormat{
    
    public static function tgl_indo($tanggal){
        $bulan = array (
          1 =>   'Januari',
          'Februari',
          'Maret',
          'April',
          'Mei',
          'Juni',
          'Juli',
          'Agustus',
          'September',
          'Oktober',
          'November',
          'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        
        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun
       
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
      }

    public static function getACertainAttrOfCollection($collection,$array_of_attr,$type)
    {
      $subset = $collection->map(function ($collection)use(&$array_of_attr,&$type) {
        if($type=="collection"){
          return collect($collection->toArray())
              ->only($array_of_attr)
              ->all();
        }else{
          return collect($collection)
              ->only($array_of_attr)
              ->all();
        }
      });
      return $subset;
    }

    public static function getNamaProdiChart($prodi)
    {
      $nama_prodi = array();
      foreach ($prodi as $key => $value) {
        $tem = explode(" ",$value['nama_program_studi']);
        if($tem[1]=="Pendidikan"){
          if($tem[2]=="Guru"){
            $tem[1] = "PG. PAUD";
            unset($tem[2]);
            unset($tem[3]);
            unset($tem[4]);
            unset($tem[5]);
            unset($tem[6]);
          }else{
            $tem[1] = "P.";
          }
        }
        if($tem[1]=="Hukum"){
          $tem[1] = "HKI";
          unset($tem[2]);
          unset($tem[3]);
          unset($tem[4]);
          unset($tem[5]);              
        }
        $nm_prodi="";
        foreach ($tem as $key => $value) {
          $nm_prodi = $nm_prodi.$value." ";
        }
        array_push($nama_prodi,$nm_prodi);
      }

      return $nama_prodi;
    }

    public static function getPeriodeAngkatan($collection)
    {
      $nama_angkatan = array();

      foreach ($collection as $key => $value) {
        $nm_ang = substr($value->id_periode,0,4);
        array_push($nama_angkatan,$nm_ang);
      }

      sort($nama_angkatan);

      return $nama_angkatan;
    }

    public static function getPeriodeTahunLulusan($collection,$attribute){
      $tahun = array();

      foreach ($collection as $key => $value) {
        $nm_ang = substr($value[$attribute],6,4);
        array_push($tahun,$nm_ang);
      }

      krsort($tahun);

      return $tahun;
    }
    

    public static function getPeriodeTahun($collection,$attribute)
    {
      $nama_angkatan = array();

      foreach ($collection as $key => $value) {
        $nm_ang = substr($value[$attribute],0,4);
        array_push($nama_angkatan,$nm_ang);
      }

      krsort($nama_angkatan);

      return $nama_angkatan;
    }

    public static function getPreviousPeriod(string $currentPeriod): ?string
      {
          if (strlen($currentPeriod) !== 5) {
              return null; // Format tidak valid
          }
      
          $year = (int)substr($currentPeriod, 0, 4);
          $semester = (int)substr($currentPeriod, 4, 1);
      
          if ($semester === 2) {
              return $year . '1';
          } elseif ($semester === 1) {
              return ($year - 1) . '2';
          }
      
          return null; // Semester tidak valid
      }
}