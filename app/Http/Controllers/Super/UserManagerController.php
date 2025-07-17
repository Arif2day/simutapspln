<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Roles;
use App\Models\RoleUsers;
use Sentinel;
use DataTables;

class UserManagerController extends Controller
{
    public function index()
    {
      $roles = Roles::all();
      return view('Admin.SUPER.users.index',compact(['roles']));
    }

    public function getUsers(Request $req)
    {
      $role = $req->role; 
      if ($req->ajax()) {
        $data = Users::with(['roles'])
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
            ->addColumn('action', function($row){
                $actionBtn =
                // '<button class="ml-1 mb-1 btn btn-sm btn-success" title="Detail User"'.                    
                //     '><i class="fa fa-eye"></i></button>'. 
                '<button class="ml-1 mb-1 btn btn-sm btn-primary editUserBtn" title="Edit User"'. 
                ' data-id="'.$row->id.'"'.
                ' data-first_name="'.$row->first_name.'"'.
                ' data-last_name="'.$row->last_name.'"'.
                ' data-email="'.$row->email.'"'.
                ' data-phone="'.$row->phone.'"'.
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
            ->rawColumns(['action'])
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
        if($user->save()){
          $res['message']="User saved successfully.";
        }else{
          $res['error']=true;
          $res['message']="User failed to save!";
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
        $user->password = Hash::make($request->password);
        if($user->save()){
          // Detach all current roles
          $user->roles()->detach();

          // Attach new role (by ID)
          $role = Sentinel::findRoleById($request->role_id);
          $role->users()->attach($user);
          $res['message']="User updated successfully.";
        }else{
          $res['error']=true;
          $res['message']="User failed to update!";
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
        $res['message']="User has been deleted.";
      }else{
        $res['error']=true;
        $res['message']="Fail to delete user!";
      }
      // redirect
      return response()->json($res);
    }
}
