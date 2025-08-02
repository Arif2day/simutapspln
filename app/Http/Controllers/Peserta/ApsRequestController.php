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
use App\Helpers\ImageHelper;
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
        $is_reviewed = $req->is_reviewed;
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

            if ($is_reviewed !== 'all') {
                $query->where('next_verificator_id', $user->id);
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
        $apsrequest = ApsRequests::with(['user','unitFrom','positionFrom','unitTo','positionTo','verificator'])
        ->where('id',$id)->first();
        $approvals = ApsApprovals::with('approver')->where('aps_request_id',$id)->get();
        
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

    public function responseRequest(Request $request) {
        $res['error']=false;
        $res['message']="";
        $res['data']='';
        try {
            $data = ApsRequests::where('id',$request->aps_request_id)->first();  
            
            $prev_step = $data->prev_step;
            $prev = $data->next_step;
            $appBy = $data->next_verificator_id;
            $next = '';
            $next_verificator_id = null;
            switch ($data->next_step) {
                case 'bpo_asal':
                    $next = 'htd_asal';
                    // cari verificator user untuk di notif
                    $verificator = DB::table('users')
                    ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                    ->join('role_users', 'users.id', '=', 'role_users.user_id')
                    ->join('roles', 'role_users.role_id', '=', 'roles.id')
                    ->where('user_placements.unit_id', $data->unit_id_from)
                    ->where('roles.slug', 'human-talent-development')
                    ->select('users.*')
                    ->limit(1)
                    ->get();
                    $next_verificator_id = $verificator[0]->id;
                    break;
                case 'htd_asal':
                    if($prev_step=='bpo_asal'){
                        $next = 'htd_tujuan';
                        // cari verificator user untuk di notif
                        $verificator = DB::table('users')
                        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('user_placements.unit_id', $data->unit_id_to)
                        ->where('roles.slug', 'human-talent-development')
                        ->select('users.*')
                        ->limit(1)
                        ->get();
                        $next_verificator_id = $verificator[0]->id;
                        break;
                    }else{
                        $next = 'htd_korporat';
                        $verificator = DB::table('users')
                        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('roles.slug', 'super-admin')
                        ->select('users.*')
                        ->limit(1)
                        ->get();
                        $next_verificator_id = $verificator[0]->id;
                        break;
                    }
                case 'htd_tujuan':
                    if($prev_step=='htd_asal'){
                        $next = 'bpo_tujuan';
                        // cari verificator user untuk di notif
                        $verificator = DB::table('users')
                        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('user_placements.unit_id', $data->unit_id_to)
                        ->where('roles.slug', 'general-manager')
                        ->select('users.*')
                        ->limit(1)
                        ->get();
                        $next_verificator_id = $verificator[0]->id;
                        break;                    
                    }else{
                        $next = 'htd_asal';
                        // cari verificator user untuk di notif
                        $verificator = DB::table('users')
                        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('user_placements.unit_id', $data->unit_id_from)
                        ->where('roles.slug', 'human-talent-development')
                        ->select('users.*')
                        ->limit(1)
                        ->get();
                        $next_verificator_id = $verificator[0]->id;
                        break;
                    }
                case 'bpo_tujuan':
                        $next = 'htd_tujuan';
                        // cari verificator user untuk di notif
                        $verificator = DB::table('users')
                        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('user_placements.unit_id', $data->unit_id_to)
                        ->where('roles.slug', 'human-talent-development')
                        ->select('users.*')
                        ->limit(1)
                        ->get();
                        $next_verificator_id = $verificator[0]->id;
                        break;                                        
                default:
                    $next = 'htd_korporat';
                    $verificator = DB::table('users')
                    ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                    ->join('role_users', 'users.id', '=', 'role_users.user_id')
                    ->join('roles', 'role_users.role_id', '=', 'roles.id')
                    ->where('roles.slug', 'super-admin')
                    ->select('users.*')
                    ->limit(1)
                    ->get();
                    $next_verificator_id = $verificator[0]->id;
                    break;
            }
            if($next_verificator_id==null)
            {
                $res['error']=true;
                $res['message']="Can't proccess, next verificator is null!";
                return response()->json($res);
            }
            if($prev=='htd_korporat'){
                $next='pemohon';
                $next_verificator_id = $data->user_id;
                $verificator = Users::where('id',$data->user_id)->get();
                $data->status = 'approved';
            }            
            $data->prev_step = $prev;
            $data->next_step = $next;
            $data->next_verificator_id = $next_verificator_id;
            if($data->save()){
                // approvals
                $approval = new ApsApprovals();
                $approval->aps_request_id = $data->id;
                $approval->step = $prev;
                $approval->approved_by = $appBy;
                $approval->approved_at = Carbon::now();
                $approval->status = 'approved';
                $approval->save();
                // documents
                if ($request->hasFile('sk_terbit')) {
                    $file = $request->file('sk_terbit');                    
                    $base64 = base64_encode(file_get_contents($file->getRealPath()));
                    $mime = $file->getMimeType(); // contoh: "application/pdf"
                    $dataUri = "data:$mime;base64,$base64";
                    
                    // Simpan path file di database atau gunakan sesuai kebutuhan
                    $pdfData = (object)[
                        'path' => 'documents/',
                        'uniqid' => "documents",
                        'pdf' => $dataUri,
                        ];
                    $docPath = ImageHelper::uploadPDF($pdfData);       

                    $adoc = new ApsDocuments();
                    $adoc->aps_request_id =  $data->id;
                    $adoc->aps_approval_id = $approval->id;
                    $adoc->document_type =  "sk_terbit";
                    $adoc->document_name = "SK Terbit";
                    $adoc->file_path = $docPath;
                    $adoc->uploaded_by = Sentinel::getUser()->id;
                    $adoc->uploaded_at = Carbon::today();
                    $adoc->save();                         
                }
                
                // jangan lupa dinotif
                foreach ($verificator as $userData) {
                    $user = Sentinel::findById($userData->id);
                    if ($user) {
                        $user->notify(new ApsRequestSubmitted($data));
                    }
                }
              $res['message']="Permohonan approved successfully.";
            }else{
              $res['error']=true;
              $res['message']="Permohonan failed to approve!";
            }
          } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
          }
        return response()->json($res);
    }

    public function responseRequestUpload(Request $request) {
        $res['error']=false;
        $res['message']="";
        $res['data']='';
        try {
            $data = ApsRequests::where('id',$request->aps_request_id)->first();  
            
            $prev_step = $data->prev_step;
            $prev = $data->next_step;
            $appBy = $data->next_verificator_id;
            $next = '';
            $next_verificator_id = null;
            switch ($data->next_step) {
                case 'bpo_asal':
                    $next = 'htd_asal';
                    // cari verificator user untuk di notif
                    $verificator = DB::table('users')
                    ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                    ->join('role_users', 'users.id', '=', 'role_users.user_id')
                    ->join('roles', 'role_users.role_id', '=', 'roles.id')
                    ->where('user_placements.unit_id', $data->unit_id_from)
                    ->where('roles.slug', 'human-talent-development')
                    ->select('users.*')
                    ->limit(1)
                    ->get();
                    $next_verificator_id = $verificator[0]->id;
                    break;
                case 'htd_asal':
                    if($prev_step=='bpo_asal'){
                        $next = 'htd_tujuan';
                        // cari verificator user untuk di notif
                        $verificator = DB::table('users')
                        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('user_placements.unit_id', $data->unit_id_to)
                        ->where('roles.slug', 'human-talent-development')
                        ->select('users.*')
                        ->limit(1)
                        ->get();
                        $next_verificator_id = $verificator[0]->id;
                        break;
                    }else{
                        $next = 'htd_korporat';
                        $verificator = DB::table('users')
                        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('roles.slug', 'super-admin')
                        ->select('users.*')
                        ->limit(1)
                        ->get();
                        $next_verificator_id = $verificator[0]->id;
                        break;
                    }
                case 'htd_tujuan':
                    if($prev_step=='htd_asal'){
                        $next = 'bpo_tujuan';
                        // cari verificator user untuk di notif
                        $verificator = DB::table('users')
                        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('user_placements.unit_id', $data->unit_id_to)
                        ->where('roles.slug', 'general-manager')
                        ->select('users.*')
                        ->limit(1)
                        ->get();
                        $next_verificator_id = $verificator[0]->id;
                        break;                    
                    }else{
                        $next = 'htd_asal';
                        // cari verificator user untuk di notif
                        $verificator = DB::table('users')
                        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('user_placements.unit_id', $data->unit_id_from)
                        ->where('roles.slug', 'human-talent-development')
                        ->select('users.*')
                        ->limit(1)
                        ->get();
                        $next_verificator_id = $verificator[0]->id;
                        break;
                    }
                case 'bpo_tujuan':
                        $next = 'htd_tujuan';
                        // cari verificator user untuk di notif
                        $verificator = DB::table('users')
                        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                        ->join('role_users', 'users.id', '=', 'role_users.user_id')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('user_placements.unit_id', $data->unit_id_to)
                        ->where('roles.slug', 'human-talent-development')
                        ->select('users.*')
                        ->limit(1)
                        ->get();
                        $next_verificator_id = $verificator[0]->id;
                        break;                                        
                default:
                    $next = 'htd_korporat';
                    $verificator = DB::table('users')
                    ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
                    ->join('role_users', 'users.id', '=', 'role_users.user_id')
                    ->join('roles', 'role_users.role_id', '=', 'roles.id')
                    ->where('roles.slug', 'super-admin')
                    ->select('users.*')
                    ->limit(1)
                    ->get();
                    $next_verificator_id = $verificator[0]->id;
                    break;
            }
            if($next_verificator_id==null)
            {
                $res['error']=true;
                $res['message']="Can't proccess, next verificator is null!";
                return response()->json($res);
            }
            if($prev=='htd_korporat'){
                $next='pemohon';
                $next_verificator_id = $data->user_id;
                $verificator = Users::where('id',$data->user_id)->get();
                $data->status = 'approved';
            }            
            $data->prev_step = $prev;
            $data->next_step = $next;
            $data->next_verificator_id = $next_verificator_id;
            if($data->save()){
                // approvals
                $approval = new ApsApprovals();
                $approval->aps_request_id = $data->id;
                $approval->step = $prev;
                $approval->approved_by = $appBy;
                $approval->approved_at = Carbon::now();
                $approval->status = 'approved';
                $approval->save();
                // documents
                if ($request->hasFile('nota_dinas')) {
                    $file = $request->file('nota_dinas');                    
                    $base64 = base64_encode(file_get_contents($file->getRealPath()));
                    $mime = $file->getMimeType(); // contoh: "application/pdf"
                    $dataUri = "data:$mime;base64,$base64";
                    
                    // Simpan path file di database atau gunakan sesuai kebutuhan
                    $pdfData = (object)[
                        'path' => 'documents/',
                        'uniqid' => "documents",
                        'pdf' => $dataUri,
                        ];
                    $docPath = ImageHelper::uploadPDF($pdfData);       

                    $adoc = new ApsDocuments();
                    $adoc->aps_request_id =  $data->id;
                    $adoc->aps_approval_id = $approval->id;
                    $adoc->document_type =  "nota_dinas";
                    $adoc->document_name = "Nota Dinas";
                    $adoc->file_path = $docPath;
                    $adoc->uploaded_by = Sentinel::getUser()->id;
                    $adoc->uploaded_at = Carbon::today();
                    $adoc->save();                         
                }
                
                // jangan lupa dinotif
                foreach ($verificator as $userData) {
                    $user = Sentinel::findById($userData->id);
                    if ($user) {
                        $user->notify(new ApsRequestSubmitted($data));
                    }
                }
              $res['message']="Dokumen uploaded successfully.";
            }else{
              $res['error']=true;
              $res['message']="Dokumen failed to upload!";
            }
          } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
          }
        return response()->json($res);
    }

    public function responseRequestReject(Request $request) {
        $res['error']=false;
        $res['message']="";
        $res['data']='';
        try {
            $data = ApsRequests::where('id',$request->aps_request_id)->first();  
            $data->status = 'rejected';
            $prev = $data->next_step;
            $appBy = $data->next_verificator_id;
            $next='pemohon';
            $next_verificator_id = $data->user_id;
            $verificator = Users::where('id',$data->user_id)->get();
            $data->prev_step = $prev;
            $data->next_step = $next;
            $data->next_verificator_id = $next_verificator_id;
            if($data->save()){
                // approvals
                $approval = new ApsApprovals();
                $approval->aps_request_id = $data->id;
                $approval->step = $prev;
                $approval->approved_by = $appBy;
                $approval->approved_at = Carbon::now();
                $approval->status = 'rejected';
                $approval->save();
                // documents
                if ($request->hasFile('surat_jawaban')) {
                    $file = $request->file('surat_jawaban');                    
                    $base64 = base64_encode(file_get_contents($file->getRealPath()));
                    $mime = $file->getMimeType(); // contoh: "application/pdf"
                    $dataUri = "data:$mime;base64,$base64";
                    
                    // Simpan path file di database atau gunakan sesuai kebutuhan
                    $pdfData = (object)[
                        'path' => 'documents/',
                        'uniqid' => "documents",
                        'pdf' => $dataUri,
                        ];
                    $docPath = ImageHelper::uploadPDF($pdfData);       

                    $adoc = new ApsDocuments();
                    $adoc->aps_request_id =  $data->id;
                    $adoc->aps_approval_id = $approval->id;
                    $adoc->document_type =  "surat_jawaban";
                    $adoc->document_name = "Surat Jawaban";
                    $adoc->file_path = $docPath;
                    $adoc->uploaded_by = Sentinel::getUser()->id;
                    $adoc->uploaded_at = Carbon::today();
                    $adoc->save();                         
                }
                
                // jangan lupa dinotif
                foreach ($verificator as $userData) {
                    $user = Sentinel::findById($userData->id);
                    if ($user) {
                        $user->notify(new ApsRequestSubmitted($data));
                    }
                }
              $res['message']="Permohonan rejected successfully.";
            }else{
              $res['error']=true;
              $res['message']="Permohonan failed to reject!";
            }
          } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
          }
        return response()->json($res);
    }
}
