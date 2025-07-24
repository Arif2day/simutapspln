<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApsRequests;
use App\Models\ApsApprovals;
use App\Models\ApsDocuments;
use App\Models\Users;
use App\Models\Notifications;
use App\Notifications\ApsRequestSubmitted;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Sentinel;
use DataTables;

class ApsRequestController extends Controller
{
    public function index() {
        return view('Admin.PESERTA.riwayat-permohonan.index');
    }

    public function getApsRequestList(Request $req) {
        if ($req->ajax()) {
            $user = Sentinel::getUser(); // atau auth()->user() jika pakai auth biasa
            $role = $user->roles->first()->slug; // sesuaikan cara ambil role-nya
            $query = ApsRequests::with(['documents', 'unitTo', 'unitFrom', 'positionTo', 'positionFrom', 'user']);

            switch ($role) {
                case 'super-admin':
                    // full akses, tidak ada filter
                    break;

                case 'general-manager':
                    $query->where(function ($q) use ($user) {
                        $q->where('unit_id_from', $user->latestPlacement->unit_id)
                          ->orWhere('unit_id_to', $user->latestPlacement->unit_id);
                    });
                    break;

                case 'human-talent-development':
                    $query->where(function ($q) use ($user) {
                        $q->where('unit_id_from', $user->latestPlacement->unit_id)
                          ->orWhere('unit_id_to', $user->latestPlacement->unit_id);
                    });
                    break;

                case 'peserta':
                default:
                    $query->where('user_id', $user->id);
                    break;
            }

            $data = $query->get();

            // $data = ApsRequests::with(['documents','unitTo','unitFrom','positionTo','positionFrom','user'])            
            // ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<a class="ml-1 mb-1 btn btn-sm btn-primary" '.   
                        'href="'.url('/permohonan-mutasi/riwayat').'/'.$row->id.'"><i class="fa fa-eye"></i></a>'
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

    public function getApsDocumentList(Request $req) {
        if ($req->ajax()) {            
            $data = ApsDocuments::with(['uploader'])->where('aps_request_id',$req->aps_request_id)
            ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<a class="ml-1 mb-1 btn btn-sm btn-primary" '.   
                        'href="'.url('/permohonan-mutasi/riwayat').'/'.$row->id.'"><i class="fa fa-rocket"></i></a>'
                    ;
                    $actionBtn = '';
                    return $actionBtn;
                })
                ->addColumn('document_view',function($row){
                    $linkDocument = ' <a target="_blank" href="'.asset($row->file_path).'">'.$row->document_name.'</a><br>';
                    return $linkDocument;
                })                
                ->editColumn('uploaded_at',function($row){
                    return $row->uploaded_at->translatedFormat('d F Y');
                })
                ->rawColumns(['action','document_view'])
                ->make(true);
          }
    }

    public function detailApsRequest(Request $request,$id) {
        $apsrequest = ApsRequests::with(['user','unitFrom','positionFrom','unitTo','positionTo'])
        ->where('id',$id)->first();
        $approvals = ApsApprovals::where('aps_request_id',$id)->get();
        
        if(Sentinel::check()->id==$apsrequest->next_verificator_id){
            if ($request->has('notification_id')) {
                $notificationId = $request->query('notification_id');
                $result = Notifications::where('id', $notificationId)->update([
                    'read_at' => Carbon::now()
                ]);
           }
        }
        $documents = ApsDocuments::where('aps_request_id',$id)->get();
        return view('Admin.PESERTA.riwayat-permohonan.detail',compact(['apsrequest','documents','approvals']));        
    }

}
