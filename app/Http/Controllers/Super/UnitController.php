<?php

namespace App\Http\Controllers\SUPER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Units;
use App\Models\UnitTypes;

use Sentinel;
use DataTables;

class UnitController extends Controller
{
    public function index() {
        $unit_types = UnitTypes::all();
        return view('Admin.SUPER.units.index',compact(['unit_types']));
    }

    public function getUnitList(Request $req) {
        if ($req->ajax()) {
            $data = Units::with(['getUnitType'])->get();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<button class="ml-1 mb-1 btn btn-sm btn-primary editUnitBtn" title="Edit Unit"'. 
                    ' data-id="'.$row->id.'"'.
                    ' data-name="'.$row->name.'"'.
                    ' data-address="'.$row->address.'"'.
                    ' data-unit_type_id="'.$row->unit_type_id.'"'.
                    ' data-toggle="modal"'.
                    ' data-target="#editUnitModal"'.                   
                        '><i class="fa fa-pen"></i></button>'.
                    '<button class="ml-1 mb-1 btn btn-sm btn-danger" title="Delete Unit" onClick="deleteUnit('.$row->id.')"'.
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
        $data = new Units();  
        $data->name = $request->name;
        $data->unit_type_id = $request->unit_type_id;
        $data->address = $request->address;
        if($data->save()){
          $res['message']="Unit saved successfully.";
        }else{
          $res['error']=true;
          $res['message']="Unit failed to save!";
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

      $data = Units::where('id',$request->id)->first();
      try {        
        $data->name = $request->name;
        $data->unit_type_id = $request->unit_type_id;
        $data->address = $request->address;
        if($data->save()){
          $res['message']="Unit updated successfully.";
        }else{
          $res['error']=true;
          $res['message']="Unit failed to update!";
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
      $data = Units::where('id',$request->id)->first();
      if ($data->delete()) {
        $res['message']="Unit has been deleted.";
      }else{
        $res['error']=true;
        $res['message']="Fail to delete unit!";
      }
      // redirect
      return response()->json($res);
    }
}
