<?php

namespace App\Helpers;

use DateTime;

class CustomFormat{
  public static function detectFileType($file)
  {
      // 1. Cek dari 'url' apakah ada indikasi mime type
      if (isset($file['url'])) {
          if (str_contains($file['url'], 'application/pdf')) {
              return 'pdf';
          }
  
          if (str_contains($file['url'], 'image/')) {
              return 'image';
          }
      }
  
      // 2. Cek dari ekstensi file (fallback)
      if (isset($file['fileName'])) {
          $ext = strtolower(pathinfo($file['fileName'], PATHINFO_EXTENSION));
          if ($ext === 'pdf') {
              return 'pdf';
          }
  
          if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
              return 'image';
          }
      }
  
      // 3. Jika tidak diketahui
      return 'unknown';
  }
  
    public static function cekStatusPensiun($dob)
    {
        $birthDate = new DateTime($dob);
        $now = new DateTime();
    
        $umur = $birthDate->diff($now);
        $umurTahun = $umur->y;
        $umurBulan = $umur->m;
    
        // Hitung tanggal ulang tahun ke-56
        $retirement56 = (clone $birthDate)->modify('+56 years');
    
        // Hitung umur dalam minggu/hari jika mendekati pensiun
        if ($umurTahun === 55 && $umurBulan >= 9) {
            $daysLeft = $now->diff($retirement56)->days;
            $weeksLeft = floor($daysLeft / 7);
            return [
                'status' => 'akan_pensiun',
                'weeks_left' => $weeksLeft,
                'days_left' => $daysLeft,
                'retirement_date' => $retirement56->format('Y-m-d'),
            ];
        }
    
        // Sudah lewat 60 tahun
        if ($umurTahun >= 60) {
            return ['status' => 'sudah_pensiun'];
        }
    
        // Selain itu
        return ['status' => 'belum_mendekati'];
    }
    

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