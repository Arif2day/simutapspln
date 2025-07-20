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
        $return = array();
        return view("Guest.beranda.index", compact([]));
    }
}
