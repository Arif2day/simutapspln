<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Helpers\customFormat;
use App\Helpers\AKMHelper;
use App\Helpers\LulusanHelper;
use App\Helpers\Dashboard\KaprodiDashboard;
use App\Helpers\Dashboard\SADashboard;


use Sentinel;
use Illuminate\Support\Arr;
use App\Models\Notifications;

class DashboardController extends Controller
{
    public function index()
    {
      $return = array();
      if ($us = Sentinel::getUser()){
          if ($us->inRole('super-admin')||$us->inRole('wakil-rektor-akademik')){
            $return = self::getSaDashboardData();
          }        
      }
      return view("Admin.dashboard", $return);
    }

    # FUNCTION TO MANAGE SADMIN DASHBOARD ======================START
    function getSaDashboardData()
    {
      // $prodi = ProdiFakultas::all();
      
      // $prodi = customFormat::getACertainAttrOfCollection($prodi,['nama_program_studi'],"collection");
      // $nama_prodi = customFormat::getNamaProdiChart($prodi);        
      $prodi = [];        
      $nama_prodi = '';        
      $return['label_akm_prodi_at_ta'] = [];
      $return['dataset_akm_prodi_at_ta'] = [];


      # Doughnout Graph
      $return['nama_prodi']=$nama_prodi;
      $return['isi_prodi'] = array();
      // $prodi = ProdiFakultas::all();
      foreach ($prodi as $key => $pro) {
        array_push(
          $return['isi_prodi'],
          count(
            Users::where('id_prodi', $pro->id_prodi)->where('status_mahasiswa', "AKTIF")->get()
            )
        );
      }
      
      $return['periode'] =[]; 

      return $return;    
    }

    public function getChartSAByPeriode(Request $req)
    {
      $prodi = ProdiFakultas::all();      
      $id_periode = $req->periode;  

      $dataset_data = SADashboard::getDatasetChartAKMSA($id_periode,$prodi);
      $prodi = customFormat::getACertainAttrOfCollection($prodi,['nama_program_studi'],"collection");
      $nama_prodi = customFormat::getNamaProdiChart($prodi);   

      $return['label_akm_prodi_at_ta'] = $nama_prodi;
      $return['dataset_akm_prodi_at_ta'] = $dataset_data;

      return response()->json($return);
    }
    # FUNCTION TO MANAGE SADMIN DASHBOARD ======================END


}
