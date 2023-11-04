<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Log;
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
            $log=\App\Log::where('user',\Auth::user()["id"])->find(session("id"));//session present AND same user
            
            if($log==null){//
                //session("id")absent||not in log||not the same users: 
                $log= new \App\Log;//adds another log row
                $log->user = \Auth::user()["id"];
                $log->start_time=date('Y-m-d H:i:s');                
                $log->ip = $_SERVER['REMOTE_ADDR'];   
                $log->userAgent =  $request->header('User-Agent');
                $log->save(); 
                session(['user' => $log->logUser]);
                session(['id' => $log->id]); 
                session(['center' => $log->logUser->center]);
            }
            //updates most recent date in any case...
            $log->end_time=date('Y-m-d H:i:s');
            $log->save();
            //...and goes on:
            //if(\Auth::user()->privilege<100) return redirect()->route('wip');//MAINTENANCE LINE
            return $next($request);
        }
        //user not present:
        return redirect()->route('login');        
    }
}
