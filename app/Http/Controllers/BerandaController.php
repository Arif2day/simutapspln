<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use iio\libmergepdf\Merger;

class BerandaController extends Controller
{
    public function index() {        
        $return = array();
        return view("Guest.beranda.index", compact([]));
    }
}
