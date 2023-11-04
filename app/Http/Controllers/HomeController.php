<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use \App\User;
use App\Http\Controllers\DataCheckController;
use Validator;
use Illuminate\Support\Facades\Session;
use Collective\Html\FormFacade as Form;
use \Illuminate\Database\Eloquent\Collection;
use Cyberduck\LaravelExcel\ExporterFacade as Exporter;
use App\Serialisers\LaravelExcelSerialiser;
use App\SVLibs\utils;
use \Carbon\Carbon;
use Browser;


use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class HomeController extends Controller
{
    
    function backup(Request $request){
            
        $dbData=config("database.connections.ridp");
        $host = $dbData["host"];//'localhost:3036';
        $port = $dbData["port"];  
        $database = $dbData["database"];//'root';
        $username = $dbData["username"];//'root';
        $dbpass = $dbData["password"];          
        $backup_file = storage_path("backups/ $database " . date("Y-m-d-H-i-s") . '.sql');//.sql
        $backup_file = ("$database " . date("Y-m-d-H-i-s") . '.sql');//.sql
        $command = "mysqldump --verbose --host=$host --port=$port --user=$username --password==$dbpass $database  "
                . ">$backup_file";
        //return $command;
        $process = new Process($command);
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo 'OUT > '.str_replace("\n", "<br>", $buffer);
            }
        });        
        echo "<br><hr>";
        
        $process = new Process('ls -d */ -lsa');
        $process->run(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo 'OUT > '.str_replace("\n", "<br>", $buffer);
            }
        });
        return response("<hr>");
    }
    function test(Request $request){ 
        $result="";
        if (\Auth::user()->privilege>=100){            
            //client info:
            $userAgent = Browser::parse($request->header('User-Agent'));
            $result.="<hr>";
            $result.="User-Agent: ".$request->header('User-Agent')."<hr>";
            $result.="Browser family: ". $userAgent->browserFamily(). " v. " .$userAgent->browserVersionMajor().".".$userAgent->browserVersionMinor().".".$userAgent->browserVersionPatch()."<hr>";
            $result.="Platform: ".$userAgent->platformName().":".$userAgent->platformFamily(). " v. " .$userAgent->platformVersionMajor().".".$userAgent->platformVersionMinor().".".$userAgent->platformVersionPatch()."<hr>";                      
        }
        return $result;                  
    }
    function __construct() 
        {$this->middleware('auth');}

    public function __invoke(Request $request){                
        $user=\Auth::user();$center=$user?$user->userCenter():NULL;
        //$t=Browser::isFirefox();
        return view("home",["request"=>$request,"user"=>$user,"center"=>$center,"logLimit"=>100]);   
        $debug="-";   
        $modelMd=\App\Patient::metadata();                       
    }
    //other miniservices:  
    
            
    public function sessions($logLimit=100){
        $privilege=\Auth::user()->privilege;
        if($privilege<100 && $logLimit>100)$logLimit=100;
        $result=[];
        foreach(\App\_s_session::whereDate('start_time', '>=', Carbon::now()->subDays($logLimit))->orderBy("start_time","DESC")->get() as $session){
            $u= \App\User::find($session->user);
            $uc=$u->userCenter();
            $logCount=$session->log()->count();
            $start=Carbon::parse($session->start_time);
            $end=Carbon::parse($session->end_time)??NULL;
            $diff=$start->diffInRealMinutes($end);
            $temp=[                    
                "data/ora"=>$start->format("d/m/Y H:i:s"),
                "sessione"=>
                    "<span>".$session->id."</span>"
                    .($logCount
                        ?"<button title='$logCount records' class='icon_button rightarrow'></button>"
                        :""),
                "utente"=>$u->username,
                "centro"=>($uc!=NULL)?$uc->title():"-",
                "livello"=>$u->UFprivilege(),
                "durata min"=>$diff,
                "browser"=>$session->userAgent,                    
            ];
            //$result[]=implode(" | ",$temp);
            $result[]=$temp;                
        }
        $result=utils::db2html(["query"=>$result,"class"=>"preview_table","noEncoding"=>true]);
        return view("utilities/sessions",["content"=> $result]);  
    }
    public function sessionLog($id){  
        $ops=[
            "A"=>["en"=>"creation","it"=>"aggiunta"],
            "M"=>["en"=>"update","it"=>"modifica"],
            "D"=>["en"=>"deletion","it"=>"cancellazione"],
        ];
        $session=\App\_s_session::find($id);
        $result="";
        if ($session){ 
            foreach($session->log()->get()as $logRec){                
                $row="<b>".$logRec->timestamp."</b>: ".$ops[$logRec->operation][\App::getLocale()]." ";
                $record=$logRec->recordObj();
                if($record){                     
                    $completion=$record->compiledFields();
                    $row.="<b><a href='".$record->editLink()."' target='blank'>".$record->formTitle()."</a></b> - " ;
                    $row.=$record->editLink();
                        //.$record->absoluteTitle(2,false,$separator="<br>")
                        //."<br>".$record->htmlFormCompletion()." " 
                        //.trans("rdp.completion").":"
                        //.$completion["compiled"]."/".$completion["all"]."(".$completion["perc"].")"
                        ;
                }else{
                    $row.= trans("rdp.".strtolower(str_replace("\\App\\","",$logRec->recordClass())));
                }
                $result.="<div class='ctrl_panel' style='min-width:20vh'>".$row."</div>";
            }   
            
        }
        return view("utilities/sessionLog",["content"=>$result]);            
    }    
    public function passwordResetToken($userId=NULL){
        $userId=($userId && Auth::user()->privilege>=100)?$userId:Auth::user()->id;
        $user=$userId?\App\User::find($userId):NULL;
        if($user)
            $pr=DB::select("SELECT * FROM _s_password_resets WHERE email = '".$user->email."'");//TODO format
            $token=(sizeof($pr)>0)?$pr[0]->token:NULL; 
            return $token?redirect(url("/password/reset/{$token}")):"";
    }
    
    public function help($title){
        //content structure : "title"=>title,"chapters"=>"help text"
        $content=trans("rdp.static.help.{$title}");
        if(is_array($content)){       
            return view("help/help",["content"=>$content]);
        }else{
            return "";
        }
        
    }    
    
    public function staticPage($type){
        //$lang=\Auth::user()->lang;        
        return view('static')->with(["title"=>trans("rdp.static.$type.title"),"content"=>trans("rdp.static.$type.content")]);        
    }       
     
    public function codeTranslationTable($format=NULL){
        $patients= \App\Patient::orderBy("code")->get();
        $patientsToEncode= \App\Patient::where("code","RLIKE","^.{11,11}$")->orderBy("code")->get();
        $patientsEncoded= \App\Patient::where("code","RLIKE","^.{13,}$")->orderBy("code")->get();
        $center= Auth::user()->center;
        $sql="SELECT centers.code as 'center', patients.code as 'new', old_code AS 'old' "
                . "FROM patients LEFT JOIN centers ON patients.parent=centers.id "
                . ($center?"WHERE parent={$center}":"")
                . " ORDER BY centers.code,patients.code";            
        if($format){//download
            //TODO
        }else{//preview            
            return utils::db2html(["select"=>$sql, "class"=>"preview_table"]);
        }
    }
              
    public function codeTranslation(Request $request){          
        if(Auth::user()->privilege>=10){
            $patients= \App\Patient::orderBy("old_code")->get();
            $patientsToEncode= \App\Patient::where("code","RLIKE","^.{11,11}$")->orderBy("code")->get();
            $patientsEncoded= \App\Patient::where("code","RLIKE","^.{13,}$")->orderBy("code")->get();
            $center= Auth::user()->center;        
            $sql="SELECT centers.code as 'center', patients.code as 'new', old_code AS 'old' "
                    . "FROM patients LEFT JOIN centers ON patients.parent=centers.id "
                    . ($center?"WHERE parent={$center}":"")
                    . " ORDER BY centers.code,patients.code";            
            return view("code_translation",["patients"=>$patients,"table"=>utils::db2html( ["select"=>$sql,"class"=>"preview_table"])]);
        }
    }        
}
