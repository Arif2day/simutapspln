<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPlacements;
use App\Models\Users;
use App\Models\Units;
use App\Models\Positions;

use DataTables;
use Carbon\Carbon;
use Sentinel;

class PermohonanMutasiController extends Controller
{
    public function index() {     
        $units = Units::all();   
        $positions = Positions::all();   
        return view('Admin.Peserta.permohonan-mutasi.index',compact(['units','positions']));
    }

    public function getMutationAvailabilityList(Request $req) {
        // Hari ini
        $today = Carbon::today();
        // 3 bulan ke depan
        $threeMonthsLater = Carbon::today()->addMonths(3);

        if ($req->ajax()) {
            $userprofile = Sentinel::getUser();
            $user = Users::with(['roles','latestPlacement.getPosition','getPlacements','latestPlacement.getUnit'])->where('id','=',$userprofile->id)->first();
            $data = UserPlacements::with(['getUnit', 'getPosition'])
            ->whereHas('getUser', function ($q) use ($today, $threeMonthsLater) {
                $q->whereRaw("DATE_ADD(birthdate, INTERVAL 56 YEAR) BETWEEN ? AND ?", [$today, $threeMonthsLater]);
            })
            ->select('unit_id', 'position_id')
            ->groupBy('unit_id', 'position_id')
            ->selectRaw('count(*) as total')
            ->get()
            ->map(function ($item) use($user) {
                return [
                    'unit_id' => $item->unit_id,
                    'unit_name' => optional($item->getUnit)->name,
                    'unit_address' => optional($item->getUnit)->address,
                    'position_id' => $item->position_id,
                    'position_name' => optional($item->getPosition)->title,
                    'total' => $item->total,
                    'a_unit_id' =>$user->latestPlacement->unit_id,
                    'a_unit_name' =>$user->latestPlacement->getUnit->name,
                    'a_unit_address' =>$user->latestPlacement->getUnit->address,
                    'a_position_id' =>$user->latestPlacement->position_id,
                    'a_position_name' =>$user->latestPlacement->getPosition->title,
                ];
            });
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<button class="ml-1 mb-1 btn btn-sm btn-primary addPengajuanMutasiBtn" title="Add Pengajuan Mutasi"'. 
                    ' data-unit_id="'.$row['unit_id'].'"'.
                    'data-unit_name="'.$row['unit_name'].'"'.
                    'data-unit_address="'.$row['unit_address'].'"'.
                    'data-position_id="'.$row['position_id'].'"'.
                    'data-position_name="'.$row['position_name'].'"'.
                    'data-allocation="'.$row['total'].'"'.
                    'data-a_unit_id="'.$row['a_unit_id'].'"'.
                    'data-a_unit_name="'.$row['a_unit_name'].'"'.
                    'data-a_unit_address="'.$row['a_unit_address'].'"'.
                    'data-a_position_id="'.$row['a_position_id'].'"'.
                    'data-a_position_name="'.$row['a_position_name'].'"'.
                    ' data-toggle="modal"'.
                    ' data-target="#addPengajuanMutasiModal"'.                   
                        '><i class="fa fa-rocket"></i> Ajukan Mutasi</button>'
                    ;
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
          }
    }
}
