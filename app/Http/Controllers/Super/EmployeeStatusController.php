<?php

namespace App\Http\Controllers\SUPER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeStatus;

use Sentinel;
use DataTables;

class EmployeeStatusController extends Controller
{
    public function index() {
        return view('Admin.SUPER.employee-status.index');
    }

    public function getEmployeeStatusList(Request $req) {
        if ($req->ajax()) {
            $data = EmployeeStatus::all();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<button class="ml-1 mb-1 btn btn-sm btn-primary editEmployeeStatusBtn" title="Edit Employee Status"'. 
                    ' data-id="'.$row->id.'"'.
                    ' data-status_name="'.$row->status_name.'"'.
                    ' data-toggle="modal"'.
                    ' data-target="#editEmployeeStatusModal"'.                   
                        '><i class="fa fa-pen"></i></button>'.
                    '<button class="ml-1 mb-1 btn btn-sm btn-danger" title="Delete Employee Status" onClick="deleteEmployeeStatus('.$row->id.')"'.
                        '><i class="fa fa-trash"></i></button>'
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
      try {
        $data = new EmployeeStatus();  
        $data->status_name = $request->status_name;
        if($data->save()){
          $res['message']="Employee Status saved successfully.";
        }else{
          $res['error']=true;
          $res['message']="Employee Status failed to save!";
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

      $data = EmployeeStatus::where('id',$request->id)->first();
      try {
        $data->status_name = $request->status_name;
        if($data->save()){
          $res['message']="Employee Status updated successfully.";
        }else{
          $res['error']=true;
          $res['message']="Employee Status failed to update!";
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
      $data = EmployeeStatus::where('id',$request->id)->first();
      if ($data->delete()) {
        $res['message']="Employee Status has been deleted.";
      }else{
        $res['error']=true;
        $res['message']="Fail to delete Employee Status!";
      }
      // redirect
      return response()->json($res);
    }
}
