<?php

namespace App\Http\Controllers\SUPER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Positions;

use Sentinel;
use DataTables;

class PositionController extends Controller
{
    public function index() {
        return view('Admin.SUPER.positions.index');
    }

    public function getPositionList(Request $req) {
        if ($req->ajax()) {
            $data = Positions::all();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn =
                    '<button class="ml-1 mb-1 btn btn-sm btn-primary editPositionBtn" title="Edit Position"'. 
                    ' data-id="'.$row->id.'"'.
                    ' data-title="'.$row->title.'"'.
                    ' data-toggle="modal"'.
                    ' data-target="#editPositionModal"'.                   
                        '><i class="fa fa-pen"></i></button>'.
                    '<button class="ml-1 mb-1 btn btn-sm btn-danger" title="Delete Position" onClick="deletePosition('.$row->id.')"'.
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
        $data = new Positions();  
        $data->title = $request->title;
        if($data->save()){
          $res['message']="Position saved successfully.";
        }else{
          $res['error']=true;
          $res['message']="Position failed to save!";
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

      $data = Positions::where('id',$request->id)->first();
      try {
        $data->title = $request->title;
        if($data->save()){
          $res['message']="Position updated successfully.";
        }else{
          $res['error']=true;
          $res['message']="Position failed to update!";
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
      $data = Positions::where('id',$request->id)->first();
      if ($data->delete()) {
        $res['message']="Position has been deleted.";
      }else{
        $res['error']=true;
        $res['message']="Fail to delete position!";
      }
      // redirect
      return response()->json($res);
    }
}
