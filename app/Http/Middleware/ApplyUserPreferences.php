<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ApplyUserPreferences
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()){
            App::setLocale(Auth::user()['lang']);
        }else{   
            App::setLocale(Config::get('app.fallback_locale'));            
        }
        return $next($request);
    }
}

