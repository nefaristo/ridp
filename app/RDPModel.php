<?php

namespace App;

use Illuminate\Support\Facades\App;
use App\SVLibs\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Collective\Html\FormFacade as Form;
use Collective\Html\HtmlFacade as Html;
use \Carbon\Carbon;
use App\SVLibs\utils;
use App\_s_log;
use Validator; 

//ADDITIONS TO PARENT: 
//everything about parents, therefore hierarchy and updatability
// 
        
class RDPModel extends ModelForm 
{   
    //eloquent overrides:
    public $timestamps=false;  
    
    public static $parentClass=NULL;//OVERRIDE - only class name of parent (ie="Patient"); 

    public function __construct($parent=NULL) {
        if($parent) $this->parent= $parent;
    }
    public function editLink(){ 
        $raw=strtolower($this->shortClass());
        $raw=config("rdp.hierarchy.$raw.editIn")??"/edit/$raw/{id}";//exception or default       
        return str_replace("{id}",$this->id,str_replace("{parent}",$this->parent,$raw));//"editIn"=>"/edit/catheter/{parent}",
    } 
    //other variables:
    public static $excludeFromCount=["id","parent","_last_update_session","old_key"]; //not to be counted for filling check and checksum
   
    //MODELFORM OVERRIDES: 
    
    public function permissions($user=NULL){//overridden by center!
        //return an array with M,D,A as keys and true/false as values
        //default updatable=(user->privilege>=10 OR user->center = patient center)
        if ($user==NULL)$user=Auth::user(); 
        $result["M"]=($user->privilege>=10);//condition A: privilege>=0...
        if(!$result["M"]){ //..or at least condition B: it's your center 
            $hierarchy=$this->ancestors();//("center",$hierarchy["center"]->id)->orWhere                
            if (array_key_exists("center",$hierarchy))//the center of the record...
                $result["M"]=($user->center==$hierarchy["center"]->id);//...is the same of the one of the user       
        }
        $result["D"]=$result["M"] && $this->id; $result["A"]=$result["M"];
        return $result;
    } 
    
    public function readOnly(){
        $permissions=$this->permissions();
        return ($this->id)?(!$permissions["M"]):(!$permissions["A"]);
    } 
    
    public function check($op="M",$input=NULL) {          
        //adds permissions (D|M|A)check to basic check:
        parent::check($op, $input);        
        $id=$this->id;$updatable=$this->permissions();
        if ($op=="D" && !$updatable["M"] || $id && !$updatable["M"] || !$updatable["A"] && !$id){                
            $this->errors->add(ALLFIELDS_,"-".trans("rdp.errors.not_updatable"));            
        }        
        return $this->errors;
    }
    
    public function checkAndUpdate($op = "M", $input = []) {
        if(session("id")) $this->_last_update_session=session("id");//field common to all standard tables
        $result=parent::checkAndUpdate($op, $input);
        if($this->errors->count()==0){
            $la= _s_log::firstOrNew([
                "session_id"=>session("id"),
                "model"=>$this->shortClass(),
                "record"=>$this->id,
                "operation"=>$op,
            ]);      
            $la->timestamp=Carbon::now()->toDateTimeString();//out of the search but updated anyway
            try{$la->save();}//actual saving    
            catch(\Exception $e){$this->errors->add(ALLFIELDS_,utils::viewStuff($la->attributes).";".$this->exceptionMessage($e,$input));}
            if($op=="D") $this->messages->add(ALLFIELDS_,trans("rdp.deleted"));
        }
        return $this->errors;
    }       
      
    //hierarchy-releated:
    
    public function parentModel(){//parent object
        $parentNSClass='\App\\'.$this::$parentClass;//namespaced class
        $result=($this::$parentClass==NULL)?NULL:$this->belongsTo ($parentNSClass,'parent');
        if(!$result && !$this->id && $this->parent)//if parent model is null, id=null (therefore new record) and parent not null workaround to retrieve the parent object
            $parentNSClass::where("id","=",$this->parent)->get();
        $result= ($result!=NULL)?$result->first():NULL;
        return $result;
    }

    public function siblings(){
        $thisClass=get_class($this);
        return $thisClass::where("parent","=",$this->parent)->where("id","<>",$this->id);
    }
    
    public function DEBparentModel(){//parent object
        $parentNSClass='\App\\'.$this::$parentClass;//namespaced class
        $result=($this::$parentClass==NULL)?NULL:$this->belongsTo ($parentNSClass,'parent');
        if(!$result && !$this->id && $this->parent)//if parent model is null, id=null (therefore new record) and parent not null workaround to retrieve the parent object
            $parentNSClass::where("id","=",$this->parent)->first();
        return $this->parent;//\App\Center::where("id","=",$this->parent)->first();
        $result= ($result!=NULL)?$result->first():NULL;
        return $result;
    }
    public function info($which=NULL){   
        if(!$which)$which=["id","custom"];
        if(is_array($which)){
            $result=[];foreach ($which as $row) {$result[$row]=$this->info($row);}
        }else{
            switch($which){
                case "id": $result= $this->title();break;
                case "class": $result= strtolower(str_replace("App\\","",  get_class($this)));break;
                //case "title": $result= $this->formTitle();break;
                case "title": $result= $this->title();break;                
                case "absoluteTitle": $result= $this->absoluteTitle();break;
                case "completion": $result=$this->compiledFields();
                case "hierarchy": $result= $this->ancestors();break;
                case "custom": $result= ""; break;
            }
        }
        return $result;
    }
            
    public function ancestors(){
        //array of models info in ascending hierarchical order:
        $result=[];$model=$this->parentModel();
        while($model!=NULL){
            $result[strtolower(str_replace("App\\","",get_class($model)))]=$model;//ie ["patient"=>model patient or null]
            $model=$model->parentModel();//sets new parent model or NULL for end of the chain
        };
        return $result;
    }
       
    public function descendants(){//true: returns plurals as key; default false:returns the config key; 
        //$result=["C"=>static::class];     
        $result=[];
        foreach(config("rdp.hierarchy.". strtolower($this->shortClass()) . ".children") as $childKey=>$listOptions){
            //$proceed=(!isset($options["if"]) || eval($options["if"]));
            $proceed=!isset($listOptions["if"]);
            if(!$proceed){
                eval("\$proceed=(" . $listOptions["if"] .");");
            }
            if($proceed){
                $childMD=config("rdp.hierarchy.". $childKey);
                $class="App\\".ucfirst($childKey);                
                $result[$childKey]=[
                    "listOptions"=>$listOptions,
                    "config"=>$childMD,
                    "models"=>$class::where("parent","=",$this->id)
// ->orderBy($childMD["orderBy"])
                        ->get(),
                ];
            }
        }
        return $result;
    } 
    
    //utilities:
    
    public function title(){
        return $this->id;        
    }//OVERRIDE - user friendly identificator (among siblings, ie patient->code for treatment)    
       
    public function formTitle(){
        //language based string to be passed as title:  
        $modelName=strtolower($this->shortClass());
        $result=trans("rdp.".$modelName);        
        if($this->id==NULL){//new record  
            $result=str_replace("{modelName}",$result,trans("rdp.new_model_form"));            
        }else{//existing record
            $result.=" " . $this->title();
        }
        return $result;
    }
    
    public function absoluteTitle($levels=100,$asc=true,$separator=" "){//user friendly ABSOLUTE identifier, 
        $titles=[];
        $i=0;
        $hier=$this->ancestors();if(!$asc) $hier=array_reverse($hier);
        foreach($hier as $model){//levels=1: only its title 
            if (++$i <= $levels) $titles[]=$model->title();
        }
        return implode($separator, $titles);
    }  
            
    public function compiledFields($excluded=NULL){
        if ($excluded==NULL)$excluded=self::$excludeFromCount;
        $all=0;$compiled=0;
        foreach(\Schema::getColumnListing($this->getTable()) as $att){
            if(!in_array ($att, $excluded)){
                $all++;
                switch($att){
                    default:
                    if($this->$att!==NULL && $this->$att!=="") $compiled++;
                }                
            }
        }
        $result["compiled"]=$compiled;
        $result["all"]=$all;
        $result["ratio"]=round($compiled/(float)$all,2);
        $result["perc"]=(100*$result["ratio"]) . "%";    
        return $result;
    }
    
    public function htmlFormCompletion($htmlAttributes=[]){
        $cf=$this->compiledFields(); 
        $htmlAttributes2=utils::addArrayStrings(["class"=>"completion_bar"],$htmlAttributes, " ");
        $htmlAttributesString=Html::attributes($htmlAttributes2);
        $result= trans("rdp.completion") . ": " ;
        $result.=$cf["compiled"] . "/" . $cf["all"] . " " ;
        $result=   "<div {$htmlAttributesString}>".
                        "<div style='width:" . $cf["perc"] . ";'></div>".
                    "</div>";
        return $result;
    }    
             
}
