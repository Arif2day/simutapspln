<?php

namespace App\Http\Controllers\SUPER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitTypes;

use Sentinel;
use DataTables;

class UnitTypeController extends Controller
{
    public function index() {
        return view('Admin.SUPER.unit-types.index');
    }

    public function getUnitTypeList(Request $req) {
        if ($req->ajax()) {
            $data = UnitTypes::all();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<button class="ml-1 mb-1 btn btn-sm btn-primary editUnitTypeBtn" title="Edit Unit Type"'. 
                    ' data-id="'.$row->id.'"'.
                    ' data-name="'.$row->unit_type_name.'"'.
                    ' data-toggle="modal"'.
                    ' data-target="#editUnitTypeModal"'.                   
                        '><i class="fa fa-pen"></i></button>'.
                    '<button class="ml-1 mb-1 btn btn-sm btn-danger" title="Delete Unit Type" onClick="deleteUnitType('.$row->id.')"'.
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
        $data = new UnitTypes();  
        $data->unit_type_name = $request->name;
        if($data->save()){
          $res['message']="Unit type saved successfully.";
        }else{
          $res['error']=true;
          $res['message']="Unit type failed to save!";
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

      $data = UnitTypes::where('id',$request->id)->first();
      try {
        $data->unit_type_name = $request->name;
        if($data->save()){
          $res['message']="Unit type updated successfully.";
        }else{
          $res['error']=true;
          $res['message']="Unit type failed to update!";
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
      $data = UnitTypes::where('id',$request->id)->first();
      if ($data->delete()) {
        $res['message']="Unit type has been deleted.";
      }else{
        $res['error']=true;
        $res['message']="Fail to delete unit type!";
      }
      // redirect
      return response()->json($res);
    }
}
