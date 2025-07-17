<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;
//use Kamaln7\Toastr\Facades\Toastr;

class SentinelMember
{
    public function handle($request, Closure $next)
    {
        if(!Sentinel::check())
        {
            //Toastr::info('This page is only accessible to guests', 'Guest Access Only');
            return Redirect::to('login');
        } else {
        	// if(!Sentinel::inRole('super-admin')){
          //
        	// }
        	// return Redirect::to('login');
            return $next($request);
        }
      }
}
