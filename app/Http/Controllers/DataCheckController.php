<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\User;
use \App\Patient;
use App\_s_datacheck;
use App\_s_datacheck_log;
use App\User_update;
use App\Therapy;
use App\_l_therapies;
use App\SVLibs\utils;
use Validator;
use App\Center;
use Carbon\Carbon;
use App\SVLibs\SVL as SVL;


class DataCheckController extends Controller
{
    public function __construct(){
        //$this->middleware('auth', ['except' => ['feedTable']]);
        $this->middleware('log');
    }
    
    //main views:  
   
    public function __invoke($args=NULL){//{"refresh"=>idCheck/"*",}
        $user=\Auth::user();
        $center=$user->privilege>=10?NULL:$user->userCenter()->id;
        $args=(array) json_decode($args);  
//return utils::viewStuff($args);     
        $dc=NULL;//selected datacheck
        if(key_exists("refresh", $args)){
            if(is_numeric($args["refresh"])){
                _s_datacheck::find($args["refresh"])->refreshLog(true);//refresh only selected
            }else{
                _s_datacheck::refreshLogs(true);//refresh all datachecks
            }
        }
        $privilege=(key_exists("privilege", $args))?args["privilege"]:$user->privilege;
//if($privilege<100) return "work in progress";         
        $dcQuery="SELECT datacheck, center, order_, privilege, COUNT(*) AS logCount "
                . "FROM _s_datacheck_log LEFT JOIN _s_datacheck ON _s_datacheck.id=_s_datacheck_log.datacheck "
                ." WHERE privilege<={$privilege}"
                .($center?" AND center={$center} ":"")
                ." GROUP BY datacheck, center"
                ." HAVING logCount>0 ORDER BY order_,center";
        $dcQuery="SELECT * FROM _s_datacheck WHERE privilege<={$privilege} ORDER BY order_";        
        //$datachecks=DB::select(DB::raw($dcQuery));$result=[]; 
        $datachecks= _s_datacheck::where("privilege","<=",$privilege)->orderBy("order_")->get();
        $result=[];        
        foreach($datachecks as $dc){
            if($center){
                $session=$dc->log($center);
                if(sizeof($session))$result[]=["dc"=>$dc,"log"=>$session];                                
            }else{
                $centersLog=$dc->centersLog();
                if(sizeof($centersLog))$result[]=["dc"=>$dc,"centersLog"=>$centersLog];
            }
        }
        return view($center?"datacheck.log":"datacheck.logByCenters",["data"=>$result]);
    }
    
    public function sql($type){
        $result="";
        if(key_exists($type,config("rdp.datacheck"))){
            $result=config("rdp.datacheck")[$type]["sql"];
            If(session("center")) {
                $code=Center::find(session("center"))->code;
                $result="SELECT * FROM ({$result} ) dummy WHERE centro='{$code}'";
            }
        }
        return $result;
    }
    public function errorList($type,$optionsList=""){ 
        $options= json_decode($optionsList,true);
        $options["sql"]=config("rdp.datacheck")[$type]["sql"];
        $options["sql"]=$this->sql($type);
/*        If(session("center")){
            $code=Center::find(session("center"))->code;
            $options["sql"]="SELECT * FROM (" . $options["sql"] . ") dummy WHERE centro='{$code}'";
        }        */
        $options["class"]="preview_table";
        $options["noEncoding"]=true;
        return response(
            config("rdp.datacheck")[$type]["desc_it"]."<hr>".
            utils::db2html($options)
        );  
        //return json_encode($typesArray);
    }
    
    public function therapies(Request $request){        
        static $pageSize=50;
        //$center=$user->privilege>=10?NULL:$user->userCenter()->id;
        if(\Auth::user()->privilege<10) return "permission denied";
        
        //        
        $op=$request->ajax()?$request->op:"";//""|list|add|delete|details for basic page print|search preview|therapy adding|specifications deleting|details of therapies with the requested specification
        $text=$request->text?(array_map (function($val){return strtolower($val);},array_filter($request->text,function($val){return $val!="";}))):[];//delete empty elements + lowercase
        $mysqlPattern="(\s*".implode($text,"|")."+\s*[,;-]*\s*)";//(any spaces + searched words + spaces + separators + spaces),
        $phpPattern="/$mysqlPattern/i";//mysql's rlike use pattern w/o terminators
        $highlight = '<span style="color:red;border:1px solid red; padding:2px 2px 2px 2px; background-color:white"><b>$1</b></span>';//for html preview
        $strikethrough = '<strike><b>$1</b></strike>';//for html preview
        $drug=$request->drug?$request->drug:NULL;        
        $drugName=$drug?_l_therapies::where("id",$drug)->first()->name_it:"";
        $ok=$request->ok?$request->ok:[];//array of flags ok to proceed
        $detailsOf=$request->detailsOf?$request->detailsOf:"";
        
        //if (count($text)==0) return ["html"=>""];
        if($text){
            $N=DB::select(DB::raw("SELECT count(*) AS N FROM therapies WHERE specification RLIKE '$mysqlPattern'"))[0]->N;
        }else{
            $N=0;
        }
        
        switch($op){
        case"list":case"proceed":
            //$result["query"]=Therapy::whereRaw("length(specification)>1")->groupBy("specification")->orderBy("specification")->skip(($request->listPage)*$pageSize)->take($pageSize)->get();
            $result["query"]=DB::select(DB::raw(
                    "SELECT count(id) AS N, MD5(specification) as checksum, specification FROM therapies GROUP BY specification HAVING specification RLIKE '$mysqlPattern' ORDER BY N desc"
            ));
            $result["html"]="<b>".($N==0?"Nessun risultato trovato":"Risultati trovati: $N")."</b><br>";
            if($N){
                $result["html"].=(
                    ($op=="list")?
                    "Per le voci selezionate, la procedura cancellerà il testo evidenziato dalla specifica. <br> All'osservazione corrispondente " . ($drug?"verrà aggiunta la terapia '$drugName' (ove non presente)":"<u>non verrà aggiunta alcuna terapia.</u>. Per sostituire il testo con una terapia propriamente immessa selezionala dal menù a tendina. Procedi solo se vuoi cancellare il testo dalla specifica senza sostituirlo con alcuna terapia.")  
                        ."<br><span id='proceedSpan'><button id='proceed' style='background-color: #ff3333'>PROCEDI</button></span>":            
                    "Le operazioni sono state eseguite sulle voci selezionate."
                );                                        
                $result["html"].= "<table class='preview_table'><tr><td></td>"
                    . "<td>Numero<br>occorrenze</td><td>Specifica<br>trovata</td>"
                    . "<td>Seleziona<br><button id ='ok' class='toggle'>☑</button></td>";
                foreach($result["query"] as $rec){
                    $okFlag=in_array($rec->checksum,$ok);;//whether the record is checked for therapy adding and specification deletion                
                    $added=0;$notAdded=0;$deleted=0;//how many actually added therapies (| not added cause present) and deleted specification excerpts 
                    if($okFlag && $op=="proceed" ){//operation proceed + flag on this group of therapy checked
                        $therapies= Therapy::where("specification",$rec->specification)->get();                             
                        foreach($therapies as $thera){
                            //1) ADDING THERAPY IF THERE'S ONE TO ADD AND IT'S NOT PRESENT
                            if($drug){                                
                                $new= Therapy::where("parent",$thera->parent)->where("type",$drug)->get();//look for already present sibling of specification therapy with the specified drug 
                                if(count($new)){//already present
                                    $notAdded+=1;
                                }else{//adding now
                                    $new= new Therapy();
                                    $new->parent=$thera->parent;
                                    $new->type=$drug;                        
                                    $new->_last_update_session=session("id");
                                    $new->save();
                                    $added+=1;     
                                }
                            }
                            //2) DELETING EXPRESSION FROM SPECIFICATION
                            $thera->specification=preg_replace($phpPattern,"",strtolower($rec->specification));
                            $thera->_last_update_session=session("id");
                            $thera->save();
                            $deleted+=1;
                        }  
                    }                
                    $result["html"].="<tr>"
                        . "<td></td>"
                        . "<td>".$rec->N."</td>"
                        . "<td>"
                            .($deleted>0?preg_replace($phpPattern,$strikethrough,$rec->specification):preg_replace($phpPattern,$highlight,$rec->specification))
                            .($op=="list"?"&nbsp;<span>&nbsp;<button class='details' style='font-size:70%' value='".$rec->specification."'> Dettagli&gt;</button></span>":"")
                            .($op=="proceed"?"<br>terapie '$drugName' aggiunte: $added ".($notAdded>0?"($notAdded già presenti)":""):"")
                        . "</td>"
                        . "<td><input type='checkbox' name='ok[]' value='" . $rec->checksum . "'". ($okFlag?" checked":""). "> </td>"
                        . "</tr>"; 
                }                                        
            }
            $result["html"].="</table>";
            return $result;
            break;
        case "details":
            if($detailsOf){//details of therapies with specifications=...
                $result["html"]="<details open><summary>Dettagli</summary><div style='border:1px black solid'>";
                $result["query"]=Therapy::where("specification","=",$detailsOf)->get();
                foreach($result["query"] as $rec){
                    $follow_up=$rec->parentModel();$treatment=$follow_up->parentModel();$patient=$treatment->parentModel(); 
                    $presentDrug=$drug?(Therapy::where("parent",$follow_up->id)->where("type",$drug)->count()):0;                      
                    //info follow up:
                    $result["html"].=
                        "<a href='https://ridp.it/edit/patient/".$patient->id."' target='_blank'>".$patient->title()."</a> &gt; "
                        . "<a href='https://ridp.it/edit/treatment/".$treatment->id."' target='_blank'>".$treatment->title()."</a> &gt; "
                        . "<a href='https://ridp.it/edit/follow_up/".$follow_up->id."' target='_blank'>".$follow_up->title()."</a>"
                        . ($drug?(Therapy::where("parent",$rec->parent)->where("type",$drug)->count()>0?"<br>(la terapia '$drugName' è già presente)":"<br>(la terapia '$drugName' non è presente)"):"")
                        ."<hr>"; 
                }
                $result["html"].="</div></details>"; 
                return $result;
            }
            break;
        default: //no op, presumably html base/first request 
            return view("datacheck.therapies",[
                //"word"=>SVL::input("text", "word", $word,["id"=>"word","style"=>"text-transform: lowercase"]),
                "drugSelect"=>SVL::input("select", "drug", "",["id"=>"drug"], ["list_source"=>"SELECT NULL as id ,' - ' as name_it UNION SELECT id,name_it from _l_therapies WHERE id<>112 ORDER BY name_it"]),            
                //"listPages"=> SVL::input("select", "listPage", "",["id"=>"listPage"], ["list_source"=>range(1,floor(Therapy::select("specification")->whereRaw("length(specification)>1")->groupBy("specification")->count()/$pageSize))]),
                "listPages"=> SVL::input("select", "listPage", "",["id"=>"listPage"], ["list_source"=>range(1,34)]),
                ]
            );                     
        }          
    }
}
