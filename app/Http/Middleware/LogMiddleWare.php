<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\_s_session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
    $wip=false; 
//$wip=true;
    if($wip && \Auth::user()["privilege"]<100) return redirect(url("/wip"));//route directly on wip.blade
        if(Auth::check($guard) ){//user present: checks [adds] and updates session                    
            $session=\App\_s_session::where('user',\Auth::user()["id"])->find(session("id"));//session present AND same user
            
            if($session==null){//
                //session("id")absent||not in log||not the same users: 
                $session= new \App\_s_session;//adds another log row
                $session->user = \Auth::user()["id"];
                $session->start_time=date('Y-m-d H:i:s');                
                $session->ip = $_SERVER['REMOTE_ADDR'];   
                $session->userAgent =  $request->header('User-Agent');                
                $session->save(); 
                session(['user' => $session->user]);
                session(['id' => $session->id]);                 
                session(['center' => \Auth::user()->center]);
            }
            session(['center' => \Auth::user()->center]);
            //updates most recent date in any case...
            $session->end_time=date('Y-m-d H:i:s');
            $session->save();
            //...and goes on:
            //if(\Auth::user()->privilege<100) return redirect()->route('wip');//MAINTENANCE LINE
            return $next($request);
        }
        //user not present:
        return redirect()->route('login');        
    }
}
