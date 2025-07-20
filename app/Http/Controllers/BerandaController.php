<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use iio\libmergepdf\Merger;
use App\Models\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class BerandaController extends Controller
{
    public function index() {   
        // $host = 'gondola.proxy.rlwy.net';
        // $port = '55038';
        // $db   = 'railway';
        // $user = 'root';
        // $pass = 'QDHnquGvLYCboTfXkGZzeTYnHeBnnjNR';

        // try {
        //     $pdo = new \PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
        //     echo "✅ Koneksi ke database berhasil.";
        // } catch (\PDOException $e) {
        //     echo "❌ Koneksi gagal: " . $e->getMessage();
        // }     
        return [
            'config' => Config::get('database.connections.mysql'),
            'env_host' => env('DB_HOST'),
            'env_port' => env('DB_PORT'),
        ];
        // $user = Users::all();
        // dd($user);
        // $return = array();
        // return view("Guest.beranda.index", compact([]));
    }
}
