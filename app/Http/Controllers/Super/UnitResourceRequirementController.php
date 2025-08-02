<?php

namespace App\Http\Controllers\SUPER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Units;
use App\Models\Positions;
use App\Models\UnitResourceRequirements;

use Sentinel;
use DataTables;

class UnitResourceRequirementController extends Controller
{
    public function index() {
        $units = Units::all();
        $positions = Positions::all();
        return view('Admin.SUPER.unit-resource-requirements.index',compact(['units','positions']));
    }

    public function getUnitResReqList(Request $req) {
      $unit = $req->unit;
      $position = $req->position;

        if ($req->ajax()) {
            $query = UnitResourceRequirements::with(['unit','position']);

            if ($unit !== 'all') {
                $query->where('unit_id', $unit);
            }
        
            if ($position !== 'all') {
                $query->where('position_id', $position);
            }
        
            $data = $query->get();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<button class="ml-1 mb-1 btn btn-sm btn-primary editUnitResReqBtn" title="Edit Unit"'. 
                    ' data-id="'.$row->id.'"'.
                    ' data-unit_id="'.$row->unit_id.'"'.
                    ' data-position_id="'.$row->position_id.'"'.
                    ' data-allocation="'.$row->allocation.'"'.
                    ' data-toggle="modal"'.
                    ' data-target="#editUnitResReqModal"'.                   
                        '><i class="fa fa-pen"></i></button>'.
                    '<button class="ml-1 mb-1 btn btn-sm btn-danger" title="Delete Unit Resource Requirement" onClick="deleteUnitResReq('.$row->unit_id.','.$row->position_id.')"'.
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
        if(UnitResourceRequirements::where('unit_id',$request->unit_id)->where('position_id',$request->position_id)->first()==null){
          $data = new UnitResourceRequirements();  
          $data->unit_id = $request->unit_id;
          $data->position_id = $request->position_id;
          $data->allocation = $request->allocation;
          if($data->save()){
            $res['message']="Unit resource requirement saved successfully.";
          }else{
            $res['error']=true;
            $res['message']="Unit resource requirement failed to save!";
          }
        }else{
          $res['error']=true;
          $res['message']="Unit resource requirement with pair of this unit and position already exist!";
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

      $data = UnitResourceRequirements::where('id',$request->id)->first();
      try {        
        $data->unit_id = $request->unit_id;
        $data->position_id = $request->position_id;
        $data->allocation = $request->allocation;
        if($data->save()){
          $res['message']="Unit resource requirement updated successfully.";
        }else{
          $res['error']=true;
          $res['message']="Unit resource requirement failed to update!";
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
      $data = UnitResourceRequirements::where('unit_id',$request->unit_id)->where('position_id',$request->position_id)->first();
      if ($data->delete()) {
        $res['message']="Unit resource requirement has been deleted.";
      }else{
        $res['error']=true;
        $res['message']="Fail to delete unit resource requirement!";
      }
      // redirect
      return response()->json($res);
    }
}
