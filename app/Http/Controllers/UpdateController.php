<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\User;
use \App\Patient;
use App\User_update;
use App\SVLibs\utils;
use Validator;
use App\SVLibs\Messages;
use Illuminate\Support\Facades\Session;

class UpdateController extends Controller
{
    
    private static function typeClass($type){
        //(TODO get everything to real class name with path)
        return "App\\". ucfirst($type);
    }
    
    private function tableColumnsInfo($table,$request=NULL){
        //returns validator-friendly array of the fields [in the request array]
        //["names"=> ["column_name"=>"user friendly name in the current language"],
        //"rules"=>["column_name"=>"rules in laravel format"]]
        $result=["names"=>[],"rules"=>[]];
        $query=DB::Select("select column_name, name_" . App::getLocale() . " as name, rules from _s_columns where table_name = ? order by id;",[$table]);
        foreach($query as $record){
            if((!$request)||(array_key_exists($record->column_name, $request))){
                $result["names"][$record->column_name]=$record->name;
                $result["rules"][$record->column_name]=$record->rules;
            }
        }
        return $result;
    }        
              
    public function __construct(){
        //$this->middleware('auth', ['except' => ['feedTable']]);
        $this->middleware('log');
    }
    
    //main views:    
    public function patients($name=""){
        if(Auth::user()->privilege>=1){
            $data['name']=$name;//TODO ????
            if(Auth::user()->privilege>=1001)
                return utils::viewStuff(Auth::user()->toArray());
            return view('patients' ,$data);
        }else{
            return redirect()->route('home');
        }
    }
    public function centers($name=""){       
        if(Auth::user()->privilege>=1){
            $data['name']=$name;//TODO ????
            return view('centers' ,$data);
        }else{
            return redirect()->route('home');
        }
    }
    public function users(Request $request,$op=""){
        $messBag=new MessageBag;
        if(Auth::user()->privilege>=10){            
            if($op && $request && $request->all()){
                $id=$request->all()["id"];
                $model=\App\User_update::find($id);if(!$model) $model=new User_update;//model
                switch($op){
                case "delete":
                    $messBag=$model->checkDelete();
                    break;
                case "save":
                    $messBag=$model->checkAndUpdate("M",$request->all());
                    break;
                }
            }
            $data['models']= \App\User_update::where("privilege","<","100")->orderBy("active","desc")->orderBy("privilege","desc")->orderBy("username")->get();
            //$data['models']=($data['models'])->all();            
            $response=view('forms.users',$data);                        
            if($messBag!=NULL){             
                //$response=redirect()->back()->withErrors($messBag);
                $response->withErrors($messBag);
                //withInput($request->input()) doesn't work with multiple forms (it copies the first value in every foreach):
                $response=$response->withInput($request->input());
            }
        }else{
            $response= redirect()->route('home');
        }
        return $response;
    }
    
    //form views:         
    public function editForm(Request $request,$parameters=""){
        //parameters=type/id/parent?
        $parameters=explode("/",$parameters);
        $class=isset($parameters[0])?self::typeClass($parameters[0]):NULL;
        $id=(isset($parameters[1]) && $parameters[1]!="-")?$parameters[1]:NULL;//workaround to avoid "//" doubls slash in GET
        $parent=isset($parameters[2])?$parameters[2]:NULL;       
        //if id null or not found open the first children or parent,
        //a new record of parent if nothing else is found
        $model=($id?($class::baseview()->where('id','=',$id)->first()):NULL); 
        if(!$model && $parent) $model=$class::baseview()->where('parent','=',$parent)->first();       
        $data=[
            "type"=>lcfirst($class),
            //"formTitle"=>$this->formTitle($model,$type),
            "model"=>$model,
            "debug"=>self::modelDebug($model,$request),
            //"layout"=>(request()->ajax() ? "layouts.main_content_only":"layouts.main"),
            "layout"=>"layouts.main",
        ];  
        return view('forms/'.lcfirst(str_replace("App\\","",$class)),$data);
    } 
    
    //form updates:    
    public function ajaxDebug(Request $request){
        $input=$request->all();  
        $all="_all_"; 
        $debug=new MessageBag;
        $debug->add($all,"~input test~");
        $debug->add($all,"token:".$input["_token"]);
        $debug->add($all,"responseType:".$input["responseType"]);
        $ops=$input["ops"];
        if(!is_array($ops) || utils::isAssoc($ops)) $ops=[$ops];
        foreach($ops as $op){ 
            $debug->add($all,"**********op ".$op["opN"]."********<br>"
                ."class:".(isset($op["class"])?$op["class"]:"N/A??")."<br>".
                "objMethod:".(isset($op["objMethod"])?$op["objMethod"]:"N/A??")."<br>".
                "data:"
            );
            //$debug->add($all,$op["data"]);
            $subdeb="";
            if(isset($op["data"])){    
                parse_str($op["data"],$deserial);
                foreach($deserial as $k1=>$v1)
                    $subdeb.="°{$k1}".($v1?":".json_encode($v1):"")."<br>";
                 
            }else{
                $subdeb="N/A";                
            }
            $debug->add($all,$subdeb);
        }      
        
        $messageRow=new Messages;
        $messageRow->errors=$debug;
        $response=[];$response["ops"][]=$messageRow;
        return $response;
    }
        
    public function modelMethod(Request $request,$class,$method,$parameters=""){//faster callable way then ops
        if($parameters){
            $parameters=explode("/",$parameters);//to array
        }else{
            $parameters=[];//explode(empty string) doesn't return an array 
        }
        $id=$request["id"]??NULL;
        //return "{$class}->{$method}(".implode(",",$parameters). ") on id={$id}"; 
        if($class && $method){ 
            $class=self::typeClass($class);
            //$model=$class::firstOrNew(['id'=>(isset($data["id"])?$data["id"]:NULL)]);
            $model=$class::firstOrNew(['id'=>$id]);
            //$model=($id?($class::baseview()->where('id','=',$id)->first()):NULL);
            if($model){
                if($request)$model->fill($request->all());
                if(count($parameters)==0){
                    return call_user_func ([$model,$method]);
                }else{
                    return call_user_func_array ([$model,$method], $parameters);
                } 
            }
        }                  
        return "{$class}->{$method}(".implode(",",$parameters). ") on id={$id}"; 
    }
    
    public function ops(Request $request){//save/add/delete et al
        /*input:
         * {_token: laravel CSRF, responseType:json/HTML,
         * ops:[{
         *  objClass: a model class, 
         *  objMethod:A|M|D   |al
         *  objData:attributes, like {id,parent,other_fields}}
         * },]}
         * output: 
         * {summary:{errors,warnings,messages},
         * ops:[{
         *   $obj,
         *   $objMethod:A|M|D (same of input),
         *   $info:{errors:{field:[messages,]},warnings:{...},messages:{...}}
         * ]}
         */    
        $response=["summary"=>["errors"=>0,"warnings"=>0,"messages"=>0],"ops"=>[]];
        //accepts A|M|D|save|add|delete, "funnel array" to bring to AMD only:
        $convOp=["save"=>"M","add"=>"A","delete"=>"D"];
        //input debug:
        if(false or isset($input["debug"]))return $this->ajaxDebug($request);        
        $input=$request->all();  
        $stopOn=(isset($input["stopOn"])?$input["stopOn"]:"errors");//stops on errors
        if(isset($input["ops"])){
            //prepares as an array of ops anyway for homogeneity, marks if it wasn't array
            $ops=$input["ops"];
            $opsIsArray=!utils::isAssoc($ops);
            if(!$opsIsArray)$ops=[$ops];
            //actual loop on ops:
            foreach($ops as $op){
                $objClass=('\\App\\' . $op["objClass"]);
                $objMethod=$op["objMethod"];
                $objData=$op["objData"];if($objData){parse_str($objData,$objData);}else{$objData=[];}  
                $model=$objClass::firstOrNew(['id'=>(isset($objData["id"])?$objData["id"]:NULL)]);
                switch($objMethod){//space for other, such as input, label, check only etc
                    default:
                        if(key_exists($objMethod,$convOp))$objMethod=$convOp[$objMethod];//converts to A|M|D
                        $model->checkAndUpdate($objMethod,$objData);       
                        $respOp=["model"=>$model,"objMethod"=>$objMethod,"info"=>$model->info];
                        $response["ops"][]=$respOp;
                        $response["summary"]["errors"]+=$model->info["errors"]->count();
                        $response["summary"]["warnings"]+=$model->info["warnings"]->count();
                        $response["summary"]["messages"]+=$model->info["messages"]->count();
                        if($model->info["errors"]->count()==0){//success
                            switch($objMethod){
                                case "D": $respOp["info"]["messages"]->add(ALLFIELDS_,trans("rdp.deleted")); break;
                                //default: $respOp["info"]["messages"]->add(ALLFIELDS_,trans("rdp.saved"));
                            }
                        }                                                
                }
                if($stopOn=="errors" && $response["summary"]["errors"] || $stopOn=="warnings" && $response["summary"]["warnings"]) break;
            }
            if(!$opsIsArray)$response["ops"]=$response["ops"][0];//back to the requested format
        }        
        //RETURN RESPONSE: 
        return response($response,200)->header('Content-Type','application/json');   
    } 
    
    public static function modelDebug($model,Request $request){
        $debug=null;
        if($model!=NULL){//debug
            $debug["current"]=["id"=>$model->id?$model->id:"N/A","model"=>(array)$model];
            $debug["updatable"]=$model->permissions();$debug["updatable"]["readonly"]=$model->readOnly()?"Y":"N";
            $debug["request"]=$request?$request->toArray():"-";
            $debug["errors"]=Session::get('errors');
            $cf=  \App\Libs\utils::compiledFields($model);           
            foreach(array_reverse($model->ancestors()) as $key=>$value){
                $debug["hierarchy"][$key]=[
                    "id"=>$value->id,
                    "model"=>(array)$value,
                    "compiled"=>$cf["compiled"] . "/" . $cf["all"],
                    "updatable"=>$value->permissions(),
                ];                
            }
            $debug["misc"]=[
                "session"=>session()->all(),
                "user"=>(array)session("user")["original"],
            ]; 
            if(get_class($model)=="follow_up"){ 
                foreach($model->children() as $key=>$value)
                    $debug["hierarchy"]["followup children"][$key]=(array)$value;
            }
            $debug=\App\SVLibs\utils::viewStuff($debug,1);
        };         
    }  
        
    public function edits($type,$parent=NULL,$orderBy=NULL){
        //if ($orderBy==NULL) $orderBy="id";
        $modelsClass = $this->typeClass($type);
        $parentModel=($this->typeClass($modelsClass::$parentClass));
        if($parentModel!=NULL && $parent!=NULL)$parentModel=$parentModel::find($parent);
        if($type=="user") $type="users";//SVSV TODO
        $models=$modelsClass::baseview();        
        if($parent!=NULL) $models=$models->where('parent','=',$parent);
        //return $d->id . ";". $parent;
        if($orderBy!=NULL) $models=$models->orderby($orderBy);
        $models=$models->get();  
        $newModel=new $modelsClass($parent);
        $data=[
            "type"=>lcfirst($type),  
            "parentModel"=>$parentModel,
            "models"=>$models,
            "newModel"=>$newModel,
        ]; 
        //return $type . "," . $parent .",". ($d==NULL?"NULL":$d->title());
        //return utils::viewStuff(["model"=>$data["newModel"],"permissions"=>$data["newModel"]->permissions()]); 
        return view('forms/'.lcfirst($type),$data); 
    }                
    public function edit($type,$id=NULL,$parent=NULL ){
        //if id null or not found open the first children or parent,
        //a new record of parent if nothing else is found
        if($id=="-") $id=NULL;//workaround to avoid // in url
        $typeClass = $this->typeClass($type); 
        $model=($id?($typeClass::baseview()->where('id','=',$id)->first()):NULL); 
        if(!$model && $parent) $model=$typeClass::baseview()->where('parent','=',$parent)->first();       
        if($model){
            return $this->form($type,$model);
        }else{
            return $this->add($type,$parent);    
        }
    } 
    public function add($type,$parent=NULL){
        $typeClass = $this->typeClass($type);       
        $model = new $typeClass($parent);//; if($parent) $model->parent=$parent;               
        return $this->form($type,$model);//view('forms/'.lcfirst($type),['data'=>$this->formData($model,$type)]);
    }  
    private function form($type,$model, Request $request=NULL){//only to unify add and save data for blade
        $debug=null;
        if($model!=NULL){//debug
            $debug["current"]=["id"=>$model->id?$model->id:"N/A","model"=>(array)$model];
            $debug["updatable"]=$model->permissions();$debug["updatable"]["readonly"]=$model->readOnly()?"Y":"N";
            $debug["request"]=$request?$request->toArray():"-";
            $debug["errors"]=Session::get('errors');          
            foreach(array_reverse($model->ancestors()) as $key=>$value){
                $debug["hierarchy"][$key]=[
                    "id"=>$value->id,
                    "model"=>(array)$value,
                    "updatable"=>$value->permissions(),
                ];                
            }
            $debug["misc"]=[ 
                "session"=>session()->all(),
                "user"=>(array)session("user")["original"],
            ]; 
            if($type=="follow_up"){ 
                foreach($model->children() as $key=>$value)
                    $debug["hierarchy"]["followup children"][$key]=(array)$value;
            }
            $debug=\App\SVLibs\utils::viewStuff($debug,1);
        };         
        $data=[
            "type"=>lcfirst($type),
            //"formTitle"=>$this->formTitle($model,$type),
            "model"=>$model,
            "debug"=>$debug,
            //"layout"=>(request()->ajax() ? "layouts.main_content_only":"layouts.main"),
            "layout"=>"layouts.main",
        ];  
        return view('forms/'.lcfirst($type),$data); 
    }
    public function field($modelType,$column,Request $request,$fieldType="input"){//single form input
        $typeClass = $this->typeClass($modelType); 
        //return $type . " " . $typeClass;
        $id=$request->all()["id"];//might be null (=>new record) as well
        $model=$typeClass::baseview()->where('id','=',$id)->first();   
        if(!$model) $model=new $typeClass;//model
        $model->fill($request->all());//mass assignment based on actual, possibly not yet saved, fields
        $result= $model->{$fieldType}($column);          
        //$result="<span>{$result}</span>"; 
        //$result= "<span>{$modelType},{$column},{$fieldType}</span>";
        
        //return response($result,200)->header('Content-Type', 'text/plain');
        return response($result,200)->header('Content-Type', 'text/html');
    }
   
      
}
