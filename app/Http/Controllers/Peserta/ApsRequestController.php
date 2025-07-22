<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApsRequests;

use DataTables;

class ApsRequestController extends Controller
{
    public function index() {
        return view('Admin.PESERTA.riwayat-permohonan.index');
    }

    public function getApsRequestList(Request $req) {
        if ($req->ajax()) {
            $data = ApsRequests::with(['documents','unitTo','unitFrom','positionTo','positionFrom','user'])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<button class="ml-1 mb-1 btn btn-sm btn-primary addPengajuanMutasiBtn" title="Add Pengajuan Mutasi"'. 
                    // ' data-unit_id="'.$row['unit_id'].'"'.
                    // 'data-unit_name="'.$row['unit_name'].'"'.
                    // 'data-unit_address="'.$row['unit_address'].'"'.
                    // 'data-position_id="'.$row['position_id'].'"'.
                    // 'data-position_name="'.$row['position_name'].'"'.
                    // 'data-allocation="'.$row['total'].'"'.
                    // 'data-user_id="'.$row['user_id'].'"'.
                    // 'data-a_unit_id="'.$row['a_unit_id'].'"'.
                    // 'data-a_unit_name="'.$row['a_unit_name'].'"'.
                    // 'data-a_unit_address="'.$row['a_unit_address'].'"'.
                    // 'data-a_position_id="'.$row['a_position_id'].'"'.
                    // 'data-a_position_name="'.$row['a_position_name'].'"'.
                    ' data-toggle="modal"'.
                    ' data-target="#addPengajuanMutasiModal" disabled'.                   
                        '><i class="fa fa-rocket"></i></button>'
                    ;
                    return $actionBtn;
                })
                ->addColumn('document_view',function($row){
                    $linkDocument ='';
                    foreach ($row->documents as $key => $value) {
                        # code...
                        $linkDocument = $linkDocument . ' <a target="_blank" href="'.asset($value->file_path).'">'.($key+1).'. '.$value->document_name.'</a><br>';
                    }
                    return $linkDocument;
                })
                ->rawColumns(['action','document_view'])
                ->make(true);
          }
    }
}
