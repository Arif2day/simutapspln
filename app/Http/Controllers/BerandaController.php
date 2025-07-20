<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use iio\libmergepdf\Merger;
use App\Models\Users;

class BerandaController extends Controller
{
    public function index() {        
        $user = Users::all();
        dd($user);
        $return = array();
        return view("Guest.beranda.index", compact([]));
    }
}
