<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\_s_datacheck;

class DatacheckMiddleWare
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
        _s_datacheck::refreshLogs(); 
        return $next($request);       
    }
}
