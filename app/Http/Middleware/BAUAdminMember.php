<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
use Sentinel;
use Redirect;

class BAUAdminMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if(!Sentinel::check())
      {
          //Toastr::info('This page is only accessible to guests', 'Guest Access Only');
          return Redirect::to('login');
      } else {
        if(!Sentinel::inRole('bau-admin')){
          return Redirect::to('notfound');
        }
        // return Redirect::to('login');
          return $next($request);
      }
    }
}
