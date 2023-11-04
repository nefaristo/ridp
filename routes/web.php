<?php
use App\SVLibs\utils; 
use App\User;

use Illuminate\Http\Request;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\DB;
//use Yajra\Datatables\Datatables;
use Request as RequestFacade;

//!! extra actions before background resource assignation is important: 
//https://laravel.com/docs/5.3/controllers#restful-supplementing-resource-controllers

//NB:
//middleware log includes checks user auth, therefore "includes" middleware auth.

//WIP:
Route::get('/clear-artisan-cache', function() {
    return Artisan::call('cache:clear');
});
Route::get('/wip',function(){return view("WIP",[]);})->name('wip');  
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

//HOME
Route::get('/','HomeController')->name("home")->middleware('log')->middleware('datacheck');  
Route::get('/query/{name}/{mode?}','HomeController@query')->middleware('log'); 
Route::get('/lists','HomeController@lists')->middleware('log'); 
Route::get('/test','HomeController@test')->middleware('log'); 
Route::get('/sessions/{logLimit?}','HomeController@sessions')->name("log")->middleware('log');
Route::get('/sessionLog/{id}','HomeController@sessionLog')->middleware('log');
Route::get('/passwordResetToken/{userId?}','HomeController@passwordResetToken')->middleware('log'); 

Route::any('/debug','HomeController@debug');
Route::any("/code_translation_table/{format?}",'HomeController@codeTranslationTable');
Route::any("/code_translation/",'HomeController@codeTranslation');



//UPDATE (medical data): middleware "log" in controller
//R
Route::get('patients/{name?}','UpdateController@patients')->name("patients");
Route::get('centers/{name?}','UpdateController@centers')->name("centers");
Route::any('users/{op?}','UpdateController@users');
Route::get('users','UpdateController@users')->name("users");

Route::get('/edit/{type}/{id}','UpdateController@edit');
Route::get('/edit/{type}/{id}/{parent?}','UpdateController@edit');
Route::get('/edits/{type}/{parent}','UpdateController@edits');
Route::get('/edits/{type}/{parent}/{order}','UpdateController@edits');
Route::any('/field/{modelType}/{column}/{fieldType?}','UpdateController@field');
//W
Route::any('/add/{type}/{parent?}','UpdateController@add');
Route::get('/editForm/{parameters?}','UpdateController@editForm')->where('parameters', '(.*)');;
Route::post('/ops','UpdateController@ops');

Route::any('/modelMethod/{class}/{method}/{parameters?}','UpdateController@modelMethod')->where('parameters', '(.*)');
Route::any('/ajax/{parameters?}','UpdateController@ajax')->where('parameters', '(.*)');
Route::post('/save/{type}','UpdateController@save'); 
Route::post('/delete/{type}','UpdateController@delete');
Route::get('/addFollowUps/{parent?}','UpdateController@addFollowUps');
Route::post('/field/{$modelType}/{$column}/{$fieldType?}','UpdateController@field');

//BACKUP
Route::get('backup/','HomeController@backup')->name("backup");

//DATA CHECK
Route::any("datacheck/logs/{args?}",'DataCheckController@logs');
Route::any("datacheck/summary/{args?}",'DataCheckController@summary');
Route::any('datacheck/therapies','DataCheckController@therapies'); 
Route::get('datacheck/{type?}','DataCheckController')->name("dataCheck");
Route::get('datacheck/sql/{id?}','DataCheckController@sql');
Route::get('datacheck/errorList/{type}/{optionsList?}','DataCheckController@errorList');


//HELP 
Route::get('/help/{title?}','HomeController@help')->name("help");//list 

//QUERIES
Route::get('queries','QueriesController@index')->name("queries");//list
Route::get('queries/debug/{id}/{options?}','QueriesController@debug');
Route::any('queries/info','QueriesController@info');
Route::get('queries/preview/{id}/{options?}','QueriesController@preview');
Route::get('queries/download/{id}/{options?}','QueriesController@download');
Route::any('queries/showParameters/{id}/{options?}','QueriesController@showParameters');

//LOG
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

//tree feed:
Route::any('/FeedTree/{type}/{filter?}/{orderby?}',function(Request $request,$type,$filter=null,$orderby=null){
    //TODO SQL FORMAT VS INJECTION!
    //error for urls like "/type//orderby", workaround: "/type/-/orderby" accepted
    if($filter=="-")$filter=null;$filter=  str_replace("*", "%", $filter);
    $QueryString=  config("rdp.tree.$type") . ($filter?" WHERE ".$filter:"") . ($orderby?" ORDER BY ".$orderby:"");
    switch($type){
        case("NO_follow_ups")://adds standard followups - currently DISABLED by NO_
            $treatId=str_replace("parent=","",$filter);
            if ($treatId){
                $treatment=\App\Treatment::find(intval($treatId));   
                if($treatment) $treatment->followUps(true);
            }
            break;
    } 
    $result = DB::select($QueryString); 
    return $request->wantsJson()?json_decode(json_encode((array) $result), true):$result;   
})->middleware('auth');

//AUTH 
Auth::routes();//http://stackoverflow.com/questions/39196968/laravel-5-3-new-authroutes/39197278#39197278

//USER PREFS
Route::get('user/{op}/{par1?}', function ($op, $par1=null) {    
    switch($op){
        case "lang":
            if (array_key_exists($par1, config('languages'))) 
                Auth::user()->lang=$par1;Auth::user()->save();
                App::setLocale($par1);
            break;            
        default:
            return redirect(route("home"));            
    }
    //return App::getLocale() . ";" . auth()->user();// $lang;
    return back();
})->middleware('log');

//OTHERS:
Route::any('/FeedPatientsTree/{code?}/{openOnly?}/{center?}',function(Request $request,$code=null,$openOnly=null,$center=null){
    //TODO CANCEL AFTER SOLVING REWRITE ISSUE!  
    //error for urls like "/type//orderby", workaround: "/type/-/orderby" accepted
    $queryString="";
    if($code!="-" AND $code!=NULL) $queryString="code like '%{$code}%'";
    if ($openOnly=="1") $queryString.=($queryString?" AND ":"")."treats_open=1";
    if ($center) $queryString.=($queryString?" AND ":"")."parent=".$center;
    $queryString=config("rdp.tree.patients") . ($queryString?" WHERE ":"") . $queryString . " ORDER BY Code";
    $result = DB::select($queryString); 
    return $request->wantsJson()?json_decode(json_encode((array) $result), true):$result;   
})->middleware('auth');          
//direct:
Route::get("/ad", function() { return Redirect::to("/adminer-4.3.1-mysql.php"); });

//DEBUG
Route::get('/systeminfo',function(Request $request){
    if(\Auth::user()->privilege>=100)
    {
        ob_start();
        phpinfo();
        $phpinfo= ob_get_contents();
        ob_get_clean();
        $mail=config("mail");$mail["password"]=substr($mail["password"],0,2)."...";
        $defaultDbName=config("database")["default"];
        $defaultDb=config("database.connections.".$defaultDbName);$defaultDb["password"]=substr($defaultDb["password"],0,2).".."; 
        
        $info=[
            "PHPINFO"=>$phpinfo,
            "APP_DEBUG"=>env('APP_DEBUG',"[nope]"),
            "APP_ENV"=>env('APP_ENV',"[nope]"),
            "DB_HOST"=>config("database.connections.rdp.host"),
            "USER"=>utils::viewStuff(\Auth::user()->getAttributes()),
            "DEFAULT DB (".$defaultDbName.")"=>utils::viewStuff($defaultDb),
            "MAIL"=>utils::viewStuff($mail),
            "SESSION"=>utils::viewStuff($request->session()->all()),            
        ];    
        $res="";
        foreach($info as $k=>$v){$res.="<details><summary>$k</summary>$v</details>";}
        return $res;            
    }
});



        
//UTILITIES
//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});
//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});
//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});
//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});
//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});
//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
  
 //BASE DEFAULT:
Route::get('/{type}','HomeController@staticPage')->middleware('log');
