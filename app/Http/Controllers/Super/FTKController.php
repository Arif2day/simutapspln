<?php

namespace App\Http\Controllers\SUPER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Units;
use App\Models\Positions;
use App\Models\UnitResourceRequirements;

use Carbon\Carbon;
use Sentinel;
use DataTables;
use Illuminate\Support\Facades\DB;

class FTKController extends Controller
{
    public function index() {
        $units = Units::all();
        $positions = Positions::all();
        return view('Admin.SUPER.ftk.index',compact(['units','positions']));
    }

    public function getFTKList(Request $req) {
      $unit = $req->unit;
      $position = $req->position;

        if ($req->ajax()) {
            // $data = $this->getDataWithoutClose56();
            $data = $this->getDataWithClose56($unit);

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('persentase_ftk', function($row){
                  return $row->persentase_ftk.'%';
                })
                ->editColumn('persentase_ftk_with_56', function($row){
                  return $row->persentase_ftk_with_56.'%';
                })
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<button class="ml-1 mb-1 btn btn-sm btn-primary editUnitResReqBtn" title="Edit Unit"'. 
                    // ' data-id="'.$row->id.'"'.
                    // ' data-unit_id="'.$row->unit_id.'"'.
                    // ' data-position_id="'.$row->position_id.'"'.
                    // ' data-allocation="'.$row->allocation.'"'.
                    ' data-toggle="modal"'.
                    ' data-target="#editUnitResReqModal"'.                   
                        '><i class="fa fa-pen"></i></button>'
                    // '<button class="ml-1 mb-1 btn btn-sm btn-danger" title="Delete Unit Resource Requirement" onClick="deleteUnitResReq('.$row->unit_id.','.$row->position_id.')"'.
                    //     '><i class="fa fa-trash"></i></button>'
                    ;
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
          }
    }

    static function getDataWithClose56($unit)
    {
      // Tanggal batas usia 56 tahun dalam 3 bulan ke depan
      $maxBirthdate = Carbon::now()->subYears(56)->addMonths(3)->format('Y-m-d');

      // Subquery alokasi per unit
      $allocationSubquery = DB::table('unit_resource_requirements')
          ->select('unit_id', DB::raw('SUM(allocation) as total_allocation'))
          ->groupBy('unit_id');

      // Query utama
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

              // Pegawai aktif yang belum mendekati usia 56
              DB::raw("COUNT(DISTINCT IF(usr.birthdate > '{$maxBirthdate}', up.user_id, NULL)) as total_pegawai_aktif"),

              // Pegawai mendekati usia 56 (kurang dari 3 bulan lagi)
              DB::raw("COUNT(DISTINCT IF(usr.birthdate <= '{$maxBirthdate}', up.user_id, NULL)) as close_56"),

              DB::raw('IFNULL(alloc.total_allocation, 0) as total_allocation'),

              // FTK aktif saja
              DB::raw("ROUND(
                  (COUNT(DISTINCT IF(usr.birthdate > '{$maxBirthdate}', up.user_id, NULL)) / 
                  NULLIF(alloc.total_allocation, 0)) * 100, 2
              ) as persentase_ftk"),

              // FTK aktif + close_56
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

          // Ambil hasil akhir
          $ftkPerUnit = $query->get();
          return $ftkPerUnit;
    }

    static function getDataWithoutClose56()
    {
        // Subquery untuk total allocation per unit
        $allocationSubquery = DB::table('unit_resource_requirements')
        ->select('unit_id', DB::raw('SUM(allocation) as total_allocation'))
        ->groupBy('unit_id');

        // Query utama
        $ftkPerUnit = DB::table('units as u')
        ->leftJoin('user_placements as up', function ($join) {
            $join->on('u.id', '=', 'up.unit_id')
                ->where('up.status', '=', '1');
        })
        ->leftJoinSub($allocationSubquery, 'alloc', function ($join) {
            $join->on('u.id', '=', 'alloc.unit_id');
        })
        ->select(
            'u.id as unit_id',
            'u.name as unit_name',
            DB::raw('COUNT(DISTINCT up.user_id) as total_pegawai_aktif'),
            DB::raw('IFNULL(alloc.total_allocation, 0) as total_allocation'),
            DB::raw('ROUND(
                (COUNT(DISTINCT up.user_id) / NULLIF(alloc.total_allocation, 0)) * 100, 2
            ) as persentase_ftk')
        )
        ->groupBy('u.id', 'u.name', 'alloc.total_allocation')
        ->get();
        return $ftkPerUnit;
    }

    public function store(Request $request)
    {
      $res['error']=false;
      $res['message']="";
      $res['data']='';
      try {
        if(UnitResourceRequirements::where('unit_id',$request->unit_id)->where('position_id',$request->position_id)->first()==null){
          $data = new UnitResourceRequirements();  
          $data->unit_id = $request->unit_id;
          $data->position_id = $request->position_id;
          $data->allocation = $request->allocation;
          if($data->save()){
            $res['message']="Unit resource requirement saved successfully.";
          }else{
            $res['error']=true;
            $res['message']="Unit resource requirement failed to save!";
          }
        }else{
          $res['error']=true;
          $res['message']="Unit resource requirement with pair of this unit and position already exist!";
        }
      } catch (\Exception $e) {
        $res['error']=true;
        $res['message']=$e->getMessage();
      }
             
      return response()->json($res);
    }

    public function update(Request $request)
    {
      $res['error']=false;
      $res['message']="";
      $res['data']='';

      $data = UnitResourceRequirements::where('id',$request->id)->first();
      try {        
        $data->unit_id = $request->unit_id;
        $data->position_id = $request->position_id;
        $data->allocation = $request->allocation;
        if($data->save()){
          $res['message']="Unit resource requirement updated successfully.";
        }else{
          $res['error']=true;
          $res['message']="Unit resource requirement failed to update!";
        }
      } catch (\Exception $e) {
        $res['error']=true;
        $res['message']=$e->getMessage();
      }
             
      return response()->json($res);
    }

    public function destroy(Request $request)
    {
      $res['error']=false;
      $res['data']='';
      $res['message']="";
      // delete
      $data = UnitResourceRequirements::where('unit_id',$request->unit_id)->where('position_id',$request->position_id)->first();
      if ($data->delete()) {
        $res['message']="Unit resource requirement has been deleted.";
      }else{
        $res['error']=true;
        $res['message']="Fail to delete unit resource requirement!";
      }
      // redirect
      return response()->json($res);
    }
}
