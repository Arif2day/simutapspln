<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPlacements;

use DataTables;
use Carbon\Carbon;

class PermohonanMutasiController extends Controller
{
    public function index() {        
        return view('Admin.Peserta.permohonan-mutasi.index');
    }

    public function getMutationAvailabilityList(Request $req) {
        // Hari ini
        $today = Carbon::today();
        // 3 bulan ke depan
        $threeMonthsLater = Carbon::today()->addMonths(3);

        if ($req->ajax()) {
            $data = UserPlacements::with(['getUnit', 'getPosition'])
            ->whereHas('getUser', function ($q) use ($today, $threeMonthsLater) {
                $q->whereRaw("DATE_ADD(birthdate, INTERVAL 56 YEAR) BETWEEN ? AND ?", [$today, $threeMonthsLater]);
            })
            ->select('unit_id', 'position_id')
            ->groupBy('unit_id', 'position_id')
            ->selectRaw('count(*) as total')
            ->get()
            ->map(function ($item) {
                return [
                    'unit_id' => $item->unit_id,
                    'unit_name' => optional($item->getUnit)->name,
                    'unit_address' => optional($item->getUnit)->address,
                    'position_id' => $item->position_id,
                    'position_name' => optional($item->getPosition)->title,
                    'total' => $item->total,
                ];
            });
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<button class="ml-1 mb-1 btn btn-sm btn-primary editEmployeeStatusBtn" title="Edit Employee Status"'. 
                    // ' data-id="'.$row->id.'"'.
                    // ' data-status_name="'.$row->status_name.'"'.
                    ' data-toggle="modal"'.
                    ' data-target="#editEmployeeStatusModal"'.                   
                        '><i class="fa fa-rocket"></i> Ajukan Mutasi</button>'
                    ;
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
          }
    }
}
