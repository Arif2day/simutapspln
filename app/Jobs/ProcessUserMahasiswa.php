<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use Sentinel;

class ProcessUserMahasiswa implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userMahasiswaData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userMahasiswaData)
    {
        $this->userMahasiswaData = $userMahasiswaData;
        // dd($this->userMahasiswaData);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->userMahasiswaData as $userMahasiswaData)
        {       
            // $siswa = new Siswa();
            // $siswa->nama = ucwords(strtolower($userMahasiswaData['nama_mahasiswa']));
            // $siswa->save();
            //prepare data mahasiswa to job
            $result = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $userMahasiswaData['nim']);
            $idregmhs = $userMahasiswaData['id_registrasi_mahasiswa'];
            $check = Users::where('id_registrasi_mahasiswa','=',$idregmhs)->get();
            if(count($check)==0){
                $checkNIM = Users::where('username','=',$result)->get();
                $suffix='';
                if(count($checkNIM)!=0){
                    $checkNIM = Users::where('username','like',$result.'%')->get();
                    for ($i=0; $i < count($checkNIM); $i++) { 
                        $suffix = $suffix."x"; 
                    }
                }
                // insert
                $credentials = [
                    'first_name'    => ucwords(strtolower($userMahasiswaData['nama_mahasiswa'])),
                    'last_name'    => "",
                    'phone' => "",
                    'username' => strval($result).$suffix,
                    'email'    => strval($result).$suffix,
                    'password' => strval($result).$suffix,
                    'id_mahasiswa' => strval($userMahasiswaData['id_mahasiswa']),
                    'id_registrasi_mahasiswa' => strval($userMahasiswaData['id_registrasi_mahasiswa']),                    
                    'id_prodi' => strval($userMahasiswaData['id_prodi']),
                    'status_mahasiswa' => strval($userMahasiswaData['nama_status_mahasiswa']),
                    'id_periode' => strval($userMahasiswaData['id_periode']),
                    'data_mahasiswa' => json_encode($userMahasiswaData),
                ];
                //find role mahasiswa
                $role = Sentinel::findRoleById(4);
                $user = Sentinel::registerAndActivate($credentials);  
                $role->users()->attach($user);  
            }else{
                // update
                $eliminate = array();
                array_push($eliminate,$idregmhs);
                $dataMahasiswa = Users::where('id_registrasi_mahasiswa','=',$idregmhs)->first();
                $check = Users::where('username','=',$result)->get();
                
                $dataMahasiswa->first_name = ucwords(strtolower($userMahasiswaData['nama_mahasiswa']));
                $dataMahasiswa->last_name = '';
                $dataMahasiswa->phone = '';
                if(count($check)==0){
                    $dataMahasiswa->username = strval($result);
                    $dataMahasiswa->email = strval($result);
                    $dataMahasiswa->password = Hash::make(strval($result));
                }
                $dataMahasiswa->id_mahasiswa = strval($userMahasiswaData['id_mahasiswa']);
                $dataMahasiswa->id_registrasi_mahasiswa = strval($userMahasiswaData['id_registrasi_mahasiswa']);                    
                $dataMahasiswa->id_prodi = strval($userMahasiswaData['id_prodi']);
                $dataMahasiswa->status_mahasiswa = strval($userMahasiswaData['nama_status_mahasiswa']);
                $dataMahasiswa->id_periode = strval($userMahasiswaData['id_periode']);
                $dataMahasiswa->data_mahasiswa = json_encode($userMahasiswaData);
                $dataMahasiswa->save();                            
            }
        }
    }
}
