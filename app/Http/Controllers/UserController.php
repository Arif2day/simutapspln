<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\HistoryNilai;
use App\Models\BiodataMahasiswa;
use App\Models\TranskripNilai;
use App\Helpers\NeoUmumHelper;
use Sentinel;
use Redirect;
use File;
use Response;

class UserController extends Controller
{
  public function login()
  {
    if (Sentinel::check())
    {
        if((Sentinel::getUser()->inRole('mahasiswa'))){
          $user = Sentinel::check();
          $this->clearTempData($user);      
        }
        return redirect('dashboard');
    }
    else
    {
        return view('Auth.index');
    }
  }

  public function postLogin(Request $request)
  {
      $response['error']=false;
      $response['message']='';
      $response['data']='';
      $credentials = [
          'email'    => $request->email,
          'password' => $request->password,
      ];

      $user = Sentinel::authenticate($credentials);
      if ($user=Sentinel::check())
      {
        if((Sentinel::getUser()->inRole('mahasiswa'))){
          $this->clearTempData($user);      
        }
          // User is logged in and assigned to the `$user` variable.
          return response()->json($response);
      }
      else
      {
          // User is not logged in
          $response['error'] = true;
          $response['message'] = "Invalid Username or Password!";
          return response()->json($response);
      }
  }

  public function userProfile()
  {
    if ($user = Sentinel::getUser())
    {
          $userprofile = Sentinel::getUser();
          $uu = Users::with(['roles','getPlacements','latestPlacement.getUnit'])->where('id','=',$userprofile->id)->get();
          return view('Admin.profile.index',array(
            'res'=>$userprofile,
            'resu'=>$uu[0]
          ));     
    }

  }

  public function update(Request $request)
  {
    $res['error']=false;
    $res['data']='';
    $res['message']='';
    $user = Sentinel::findById($request->id);
    $user->first_name = ucwords(strtolower($request->fname));
    $user->last_name = ucwords(strtolower($request->lname));
    $user->email = strtolower($request->email);
    $user->phone = $request->phone;
    if($user->save()){      
      $res['message']='Profil berhasil diupdate.';
      $res['data']=$user;
    }else{
      $res['error']=true;
      $res['message']='Profil gagal diupdate!';
    }

    return response()->json($res);
  }

  public function updatePassword(Request $request)
  {
      $res['error']=false;
      $res['message']="";
      $res['data']="";

      $user = Users::where('email','=',$request->email)->first();

      $credentials = [
    'email'    => $user->email,
    'password' => $request->oldPwd,
    ];

    if(!Sentinel::authenticate($credentials))
    {
      $res['error']=true;
      $res['message']="Email dan old password tidak ditemukan!";
      return response()->json($res);
    }else {
      $user = Sentinel::findById($request->id);
              $user->password = Hash::make($request->newPwd);
              $user->save();
      $res['data']=$user;
      $res['message']="Password berhasil diupdate.";
      return response()->json($res);
    }
  }

  public function logout()
  {
    if((Sentinel::getUser()->inRole('mahasiswa'))){
      $user = Sentinel::check();
      $this->clearTempData($user);      
    }

    Sentinel::logout();
    return redirect('/login');
  }

  function clearTempData($user){
    $hisNilai = HistoryNilai::where('id_registrasi_mahasiswa','=',$user->id_registrasi_mahasiswa);
    $hisNilai->delete();

    $bio = BiodataMahasiswa::where('id_registrasi_mahasiswa','=',$user->id_registrasi_mahasiswa);
    $bio->delete();

    $tra = TranskripNilai::where('id_registrasi_mahasiswa','=',json_decode($user->data_mahasiswa,true)['id_registrasi_mahasiswa']);
    $tra->delete();
  }
}
