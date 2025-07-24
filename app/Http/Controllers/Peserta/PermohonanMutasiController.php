<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPlacements;
use App\Models\Users;
use App\Models\Units;
use App\Models\Positions;
use App\Models\ApsRequests;
use App\Models\ApsDocuments;
use App\Helpers\ImageHelper;
use App\Helpers\customFormat;
use App\Notifications\ApsRequestSubmitted;

use DataTables;
use Carbon\Carbon;
use Sentinel;
use DB;

class PermohonanMutasiController extends Controller
{
    public function index() {     
        $units = Units::all();   
        $positions = Positions::all();   
        return view('Admin.PESERTA.permohonan-mutasi.index',compact(['units','positions']));
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
                    'user_id' =>$user->id,
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
                    'data-user_id="'.$row['user_id'].'"'.
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

    public function store(Request $request) {
        $res['error']=false;
        $res['message']="";
        $res['data']='';

        $documents = json_decode($request->input('documents'), true);

        // cari gm user untuk di notif
        $gmUsers = DB::table('users')
        ->join('user_placements', 'users.id', '=', 'user_placements.user_id')
        ->join('role_users', 'users.id', '=', 'role_users.user_id')
        ->join('roles', 'role_users.role_id', '=', 'roles.id')
        ->where('user_placements.unit_id', $request->unit_id_from)
        ->where('roles.slug', 'general-manager')
        ->select('users.*')
        ->limit(1)
        ->get();

        try {
            $mode = $request->mode_simpan;
            $prev_step = "pemohon";
            $next_step = $mode=="draft"?"pemohon":"bpo_asal";
            // save as draft/submitted
            $data = new ApsRequests();  
            $data->user_id = $request->user_id;
            $data->status = $mode;
            $data->prev_step = $prev_step;
            $data->next_step = $next_step;
            $data->next_verificator_id = $gmUsers[0]->id;
            $data->unit_id_to = $request->unit_id_to;
            $data->unit_id_from = $request->unit_id_from;
            $data->position_id_to = $request->position_id_to;
            $data->position_id_from = $request->position_id_from;
            $data->user_id = $request->user_id;
            if($data->save()){
                foreach ($documents as $key => $doc) {
                    if(customFormat::detectFileType($doc)=="pdf"){
                        $pdfData = (object)[
                            'path' => 'documents/',
                            'uniqid' => "documents",
                            'pdf' => $doc['url'],
                          ];
                        $docPath = ImageHelper::uploadPDF($pdfData);
                    }else{
                        $imageData = (object)[
                            'path' => 'documents/',
                            'uniqid' => "documents",
                            'image' => $doc['url'],
                          ];
                        $docPath = ImageHelper::uploadImage($imageData);
                    }
                    $adoc = new ApsDocuments();
                    $adoc->aps_request_id =  $data->id;
                    $adoc->document_type =  "permohonan";
                    $adoc->document_name = $doc['note'];
                    $adoc->file_path = $docPath;
                    $adoc->uploaded_by = $request->user_id;
                    $adoc->uploaded_at = Carbon::today();
                    $adoc->save();
                }          
                

                foreach ($gmUsers as $userData) {
                    $user = Sentinel::findById($userData->id);
                    if ($user) {
                        $user->notify(new ApsRequestSubmitted($data));
                    }
                }
            $res['message']="Permohonan mutasi saved successfully.";
            }else{
            $res['error']=true;
            $res['message']="Permohonan mutasi failed to save!";
            }
        } catch (\Exception $e) {
            $res['error']=true;
            $res['message']=$e->getMessage();
        }
                
        return response()->json($res);
    }
}
