<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Helpers\customFormat;
use App\Models\Units;
use App\Models\Positions;
use App\Models\Users;
use App\Models\UserPlacements;
use App\Models\Roles;
use App\Models\RoleUsers;
use App\Models\EmployeeStatus;
use Sentinel;
use DataTables;

class UserManagerController extends Controller
{
    public function index()
    {
      $roles = Roles::all();
      $units = Units::all();
      $positions = Positions::all();
      $employee_statuses = EmployeeStatus::all();
      return view('Admin.SUPER.users.index',compact(['roles','units','positions','employee_statuses']));
    }

    public function getUsers(Request $req)
    {
      $role = $req->role; 
      if ($req->ajax()) {
        $data = Users::with(['roles','getPlacements','latestPlacement.getUnit'])
        ->whereHas('roles',function ($query) use(&$role) {
          if($role=="all"){
                return $query;
            }else{
                  return $query->where('id', '=', $role);
              }
        })
        ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('retire',function($row){
              $retire = customFormat::cekStatusPensiun($row->birthdate);
              if($retire['status']=='akan_pensiun'){
                $reInfo = '<span class="text-sm badge badge-warning">'.$retire['weeks_left'].' weeks left</span>';
              }else if($retire['status']=='sudah_pensiun'){
                $reInfo = '<span class="text-sm badge badge-secondary">retired.</span>';
              }else{
                $reInfo = '<span class="text-sm badge badge-success">Not yet.</span>';
              }
              return $reInfo;
            })
            ->addColumn('last_status',function($row){
              if(count($row->getPlacements)==0){
                return "";
              }else{
                if($row->getPlacements[count($row->getPlacements)-1]->status==1){
                  return 'Aktif';
                }else{
                  return 'Nonaktif';
                }
              }
            })
            ->addColumn('action', function($row){
              $placement = '<button class="ml-1 mb-1 btn btn-sm detailUserBtn '.(count($row->getPlacements)==0?"btn-secondary":"btn-success").'" title="Detail User"'.  
              ' data-id="'.$row->id.'"'.
              ' data-first_name="'.$row->first_name.'"'.
              ' data-last_name="'.$row->last_name.'"'.
              ' data-email="'.$row->email.'"'.
              ' data-phone="'.$row->phone.'"'.
              ' data-birthdate="'.$row->birthdate.'"'.
              ' data-role="'.($row->roles->first()->id ?? "").'"'.
              // $placement_data.
              ' data-toggle="modal"'.
              ' data-target="#detailUserModal"'.                  
                  '><i class="fa fa-sitemap"></i></button>';
                $actionBtn =
                $placement. 
                '<button class="ml-1 mb-1 btn btn-sm btn-primary editUserBtn" title="Edit User"'. 
                ' data-id="'.$row->id.'"'.
                ' data-first_name="'.$row->first_name.'"'.
                ' data-last_name="'.$row->last_name.'"'.
                ' data-email="'.$row->email.'"'.
                ' data-phone="'.$row->phone.'"'.
                ' data-birthdate="'.$row->birthdate.'"'.
                ' data-role="'.($row->roles->first()->id ?? "").'"'.
                ' data-toggle="modal"'.
                ' data-target="#editUserModal"'.                   
                    '><i class="fa fa-pen"></i></button>'.
                '<button class="ml-1 mb-1 btn btn-sm btn-danger" title="Delete User" onClick="deleteUser('.$row->id.')"'.
                    '><i class="fa fa-trash"></i></button>'
                // '<a " class="delete btn btn-danger btn-sm">Delete</a>'
                ;
                return $actionBtn;
            })
            ->addColumn('unit_address', function($row) {
              $address = $row->latestPlacement && $row->latestPlacement->getUnit
              ? $row->latestPlacement->getUnit->address
              : '';

              return $address;
            })
            ->rawColumns(['action','retire','last_status','unit_address'])
            ->make(true);
      }
    }

    public function store(Request $request)
    {
      $res['error']=false;
      $res['message']="";
      $res['data']='';

      $credentials = [
        'first_name'    => ucwords(strtolower($request->fname)),
        'last_name'    => ucwords(strtolower($request->lname)),
        'email'    => strtolower($request->email),
        'password' => $request->password,
      ];
        $role = Sentinel::findRoleById($request->role);
      try {
        $user = Sentinel::registerAndActivate($credentials);  
        $role->users()->attach($user);  
        $user->phone = $request->phone;
        $user->birthdate = $request->birthdate;
        if($user->save()){
          $res['message']="Employee saved successfully.";
        }else{
          $res['error']=true;
          $res['message']="Employee failed to save!";
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

      $user = Sentinel::findById($request->id);
      try {
        $user->first_name = $request->fname;
        $user->last_name = $request->lname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->birthdate = $request->birthdate;
        $user->password = Hash::make($request->password);
        if($user->save()){
          // Detach all current roles
          $user->roles()->detach();

          // Attach new role (by ID)
          $role = Sentinel::findRoleById($request->role_id);
          $role->users()->attach($user);
          $res['message']="Employee updated successfully.";
        }else{
          $res['error']=true;
          $res['message']="Employee failed to update!";
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
      $user = Sentinel::findById($request->id);
      if ($user->delete()) {
        $res['message']="Employee has been deleted.";
      }else{
        $res['error']=true;
        $res['message']="Fail to delete employee!";
      }
      // redirect
      return response()->json($res);
    }
    
    public function getUserPlacements(Request $req)
    {
      $user_id = $req->user_id; 
      if ($req->ajax()) {
        $data = UserPlacements::with(['getUnit','getPosition'])
        ->where('user_id', $user_id)
        ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){     
                $actionBtn =
                '<button class="ml-1 mb-1 btn btn-sm btn-primary editUserPlacementBtn" title="Edit User Placement"'. 
                ' data-id="'.$row->id.'"'.
                ' data-user_id="'.$row->user_id.'"'.
                ' data-unit_id="'.$row->unit_id.'"'.
                ' data-position_id="'.$row->position_id.'"'.
                ' data-placement_start="'.$row->placement_start.'"'.
                ' data-placement_end="'.$row->placement_end.'"'.
                ' data-placement_end_reason="'.$row->placement_end_reason.'"'.
                ' data-status="'.$row->status.'"'.                 
                    '><i class="fa fa-pen"></i></button>'.
                '<button class="ml-1 mb-1 btn btn-sm btn-danger" title="Delete User Placement" onClick="deleteUserPlacement('.$row->id.')"'.
                    '><i class="fa fa-trash"></i></button>'
                ;
                return $actionBtn;
            })
            ->editColumn('status',function($row){
              if($row->status==1){
                return 'Aktif';
              }else{
                return 'Nonaktif';
              }
            })
            ->rawColumns(['action'])
            ->make(true);
      }
    }
    
    public function storePlacement(Request $request)
    {
      $res['error']=false;
      $res['message']="";
      $res['data']='';

        $data = new UserPlacements();
      try {
        $data->user_id = $request->user_id;
        $data->unit_id = $request->unit_id;
        $data->position_id = $request->position_id;
        $data->placement_start = $request->placement_start;
        $data->placement_end = $request->placement_end;
        if($request->placement_end_reason!=-1){
          $data->placement_end_reason = $request->placement_end_reason;
        }
        $data->status = $request->status;
        if($data->save()){
          $res['message']="Employee placement saved successfully.";
        }else{
          $res['error']=true;
          $res['message']="Employee placement failed to save!";
        }
      } catch (\Exception $e) {
        $res['error']=true;
        $res['message']=$e->getMessage();
      }
             
      return response()->json($res);
    }

    public function updatePlacement(Request $request)
    {
      $res['error']=false;
      $res['message']="";
      $res['data']='';

      $data = UserPlacements::where('id',$request->id)->first();
      try {
        $data->unit_id = $request->unit_id;
        $data->position_id = $request->position_id;
        $data->placement_start = $request->placement_start;
        $data->placement_end = $request->placement_end;
        if($request->placement_end_reason!=-1){
          $data->placement_end_reason = $request->placement_end_reason;
        }
        $data->status = $request->status;
        if($data->save()){
          $res['message']="Employee placement updated successfully.";
        }else{
          $res['error']=true;
          $res['message']="Employee placement failed to update!";
        }
      } catch (\Exception $e) {
        $res['error']=true;
        $res['message']=$e->getMessage();
      }
             
      return response()->json($res);
    }
    
    public function destroyPlacement(Request $request)
    {
      $res['error']=false;
      $res['data']='';
      $res['message']="";
      // delete
      $user = UserPlacements::where('id',$request->id)->first();
      if ($user->delete()) {
        $res['message']="Employee placement has been deleted.";
      }else{
        $res['error']=true;
        $res['message']="Fail to delete employee placement!";
      }
      // redirect
      return response()->json($res);
    }
}
