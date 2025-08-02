<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\APSRequests;
use App\Models\Units;
use App\Helpers\customFormat;
use App\Helpers\AKMHelper;
use App\Helpers\LulusanHelper;
use App\Helpers\Dashboard\KaprodiDashboard;
use App\Helpers\Dashboard\SADashboard;


use Sentinel;
use Illuminate\Support\Arr;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
      $return['ftkPerUnit'] = $this->getDataWithClose56();
      if(Sentinel::getUser()->inRole('peserta')){
        $return['need_reviews'] = APSRequests::where('next_verificator_id', $us->id)
        ->where('status','!=','approved')
        ->where('status','!=','rejected')
        ->count();
      }else{
        $return['need_reviews'] = APSRequests::where('next_verificator_id', $us->id)->count();
      }
      
      $return['units'] = Units::all()->count();
      $return['own_unit'] = Users::with(['latestPlacement.getUnit'])->where('id',$us->id)->first();
      $return['own_unit_count'] = $this->getDataWithClose56(Users::with(['latestPlacement.getUnit'])->where('id',$us->id)->first()->latestPlacement->unit_id);
      // $return['user_unit'] = 
      return view("Admin.dashboard", $return);
    }

    static function getDataWithClose56($unit="all")
    {
        $maxBirthdate = Carbon::now()->subYears(56)->addMonths(3)->format('Y-m-d');

        $allocationSubquery = DB::table('unit_resource_requirements')
            ->select('unit_id', DB::raw('SUM(allocation) as total_allocation'))
            ->groupBy('unit_id');

        $query = DB::table('units as u')
            ->leftJoin('user_placements as up', function ($join) {
                $join->on('u.id', '=', 'up.unit_id')
                    ->where('up.status', '=', '1');
            })
            ->leftJoin('users as usr', 'up.user_id', '=', 'usr.id')
            ->leftJoinSub($allocationSubquery, 'alloc', function ($join) {
                $join->on('u.id', '=', 'alloc.unit_id');
            })
            ->select(
                'u.id as unit_id',
                'u.name as unit_name',
                DB::raw("COUNT(DISTINCT IF(usr.birthdate > '{$maxBirthdate}', up.user_id, NULL)) as total_pegawai_aktif"),
                DB::raw("COUNT(DISTINCT IF(usr.birthdate <= '{$maxBirthdate}', up.user_id, NULL)) as close_56"),
                DB::raw('IFNULL(alloc.total_allocation, 0) as total_allocation'),
                DB::raw("ROUND(
                    (COUNT(DISTINCT IF(usr.birthdate > '{$maxBirthdate}', up.user_id, NULL)) / 
                    NULLIF(alloc.total_allocation, 0)) * 100, 2
                ) as persentase_ftk"),
                DB::raw("ROUND(
                    ((COUNT(DISTINCT IF(usr.birthdate > '{$maxBirthdate}', up.user_id, NULL)) + 
                      COUNT(DISTINCT IF(usr.birthdate <= '{$maxBirthdate}', up.user_id, NULL))) / 
                      NULLIF(alloc.total_allocation, 0)) * 100, 2
                ) as persentase_ftk_with_56")
            )
            ->groupBy('u.id', 'u.name', 'alloc.total_allocation');

        // Tambahkan filter jika bukan 'all'
        if ($unit !== 'all') {
            $query->where('u.id', $unit);
        }

        // Batasi maksimal 5 unit (misalnya berdasarkan alokasi terbanyak)
        $query->orderByDesc('alloc.total_allocation')->limit(3);

        return $query->get();
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
