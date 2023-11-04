<?php
/*
 * extends Model with html output utilities, additional attributes property, language utilities, output utilities 
 */
namespace App\SVLibs;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\SVLibs\SVL;
use Collective\Html\FormFacade as Form;
use Collective\Html\HtmlFacade as Html;
use App\SVLibs\utils;
use Illuminate\Support\MessageBag;
use App\SVLibs\MultiMessageBag;
use Validator;
        
define ("ALLFIELDS_","_all_");//error label for general error 

class ModelForm extends Model{   
    //belongsToMany: list of fields with array values designed to update cross reference tables.
    //format: ['fieldname'=>'\App\other class name','its table','this field name in the table','other field in the table']//that is, key=>args of belongstomany
    protected $belongsToMany=[];
    //appends: eloquent-defined protected that appends the attributes created with Accessors below ("getXyzAttribute()"):
    protected $appends=["info"];
    //info returns an array with three levels of messageBags:
    protected $errors;protected $warnings;protected $messages;
    //metaDataArray is set once per class by the static function metadata
    protected static $metadataArray=[];
    
    
    //OVERRIDES:
    public function scopeBaseview ($query){return $query->from($this->getTable());}//DEFAULT: raw table
    
    public function fill(array $attributes) {//see below
        //DATA SANITATION 1 (on inputed attributes): UNCHECKED BOOLEANS ARE ADDED:
        $dbTable=$this->getTable();//BEFORE there was this??? : str_replace("app\\","",strtolower($this->getTable())); 
        foreach(\Schema::getColumnListing($dbTable) as $attribute){//loop on all attributes...
            $colInfo=self::metadata()[$attribute]??NULL;
            if($colInfo["dom_type"]=="checkbox"){//...having checkbox as metadata dom type...
                if(!array_key_exists($attribute, $attributes)) $attributes[$attribute]=0;//...adds a zero attribute
            }
        }
        //ACTUAL FILL:
        parent::fill($attributes);
        //DATA SANITATION 2: corrects fill: null!=0!=empty...
        foreach($attributes as $name=>$value){
            //"The switch statement applies loose comparison...", so:
            if($value==NULL && !in_array($name,$this->guarded))$this->{$name}=  NULL;//(yes, input can be NULL)
        }
        //DATA SANITATION 3 (on model attributes):  DATES="" => null where needed (problem seemingly solved in 5.4)
        foreach($this->dates as $name){//carbon doesn't like "" as an input
            try{
                $dummy=$this->{$name};//triggers \InvalidArgumentException "data missing" if it's ""
            }catch( \InvalidArgumentException $e ){//correct by setting to NULL (accepted)
                $this->{$name}=NULL;
            }catch (\Exception $e) {//other errors intercepted as usual
                $result->add(ALLFIELDS_,$this->exceptionMessage($e, $input));
            }
        } 
        return $this;//as vendor parent would have
    }
    
    //INFO-RELATED: 
    //errors and warnings returned as $this->info[errors=>messageBag,warnings=>messageBag]        
      
    protected function clearInfo(){
        $this->errors=new \Illuminate\Support\MessageBag();
        $this->warnings=new \Illuminate\Support\MessageBag();
        $this->messages=new \Illuminate\Support\MessageBag();
    }
    
    public function getInfoAttribute(){//Accessor $this->info=["errors"=>,"warnings"=>] and whatever serviceable info
        if(!isset($this->errors))$this->clearInfo();
        return ["errors"=>$this->errors,"warnings"=>$this->warnings,"messages"=>$this->messages,"shortClass"=>$this->shortClass()];        
                
    }
    
    protected function mergeInfo($mergeThis){
        if(isset($mergeThis["errors"])) $this->errors->merge($mergeThis["errors"]);
        if(isset($mergeThis["warnings"])) $this->warnings->merge($mergeThis["warnings"]);
        if(isset($mergeThis["messages"])) $this->messages->merge($mergeThis["messages"]);
    }   
            
    //CHECK-RELATED:        
              
    public function check($op="M",$input=NULL){   
        $this->clearInfo();
        if($op!="D"){                       
            $this->fill($input);
            //ACTUAL BASIC VALIDATOR OF THE DATA (if it's not deleting:            
            $dbTable=str_replace("app\\","",strtolower($this->getTable()));
            $validatorArray=["names"=>[],"rules"=>[]];//"names"=>["column_name"=>"user friendly name in the current language"],"rules"=>["column_name"=>"rules in laravel format"]]
            $query=DB::Select("select COALESCE(column_name,field_name) AS field_name, name_" . App::getLocale() . " as name, rules from _s_columns where table_name = ? order by id;",[$dbTable]);
            $attributes=$this->getAttributes();
            foreach($query as $record){
                if(array_key_exists ($record->field_name, $attributes)){
                    $validatorArray["names"][$record->field_name]=$record->name;
                    $validatorArray["rules"][$record->field_name]=$record->rules;
                } 
            }        
            $val=Validator::make($this->getAttributes(),$validatorArray["rules"]);//creates validator based on db service table
            $val->setAttributeNames($validatorArray["names"]);//[fieldname=>userfriendlyname,..]                
            $this->errors->merge($val->errors());
        }        
        return ($this->errors);
    }                       
    
    public function checkAndUpdate($op="M",$input=NULL){        
        //VALIDATOR on array (default and/or overrides): 
        $this->check($op,$input);//errors returned and also put in $this->errors
        if($this->errors->isEmpty()){
            if($op=="D"){
                try{$this->delete();} 
                catch(\Exception $e){$this->errors->add(ALLFIELDS_,$this->exceptionMessage($e));}
            }else{              
                try{$this->save();}//actual saving 
                catch(\Exception $e){$this->errors->add(ALLFIELDS_,$this->exceptionMessage($e,$input));}
                //$this->errors->add(ALLFIELDS_,utils::viewStuff($this->original));  
                if($this->errors->isEmpty()){//saves cross reference tables:
                    foreach($this->belongsToMany as $field=>$info){
                        if(isset($input[$field])){//if the input is there and it's labeld as belongstomany:
                            $btm=call_user_func_array([$this,"belongsToMany"],$info);
                            $btm->sync($input[$field]);
                        }
                    }
                }
            }             
        }              
        return ($this->errors);
    }
   
    //HTML RELATED (overrides and otherwise):
    
    public function openForm($htmlAttributes=array()){
        $htmlAttributes=utils::addArrayStrings($htmlAttributes,
            [
                "data-class"=>ucfirst($this->shortClass()),
                "data-id"=>ucfirst($this->shortClass())."_".$this->id,                
                "data-csrf-token"=>csrf_token(),
                "data-confirm_out"=>($this->formTitle(). ": " . trans('rdp.dirty_form_exit')),
                "data-confirm_delete"=>($this->formTitle(). ": " . trans('rdp.delete_confirm')),
            ]
        );
        if($this->readOnly())$htmlAttributes=utils::addArrayStrings($htmlAttributes,['class'=>"sv_readonly"]);
        return Form::model($this,$htmlAttributes); //it echoes token too
    }
    
    public function closeForm(){return Form::close();}        
    
    public function input($name,$htmlAttributes=[],$options=[]){  
        $result="";      
        if ($this->readOnly()) $htmlAttributes=utils::addArrayStrings ($htmlAttributes, ["disabled"=>"disabled"]);
        $modelMd=self::metadata();
        //METADATA BASED ATTRIBUTES:
        if(isset($modelMd[$name])){
            $md=$modelMd[$name];
            //VALUE:
            if (key_exists($name,$this->belongsToMany)){//array value is referred to a cross-ref table:                
                $btm=call_user_func_array([$this,"belongsToMany"],$this->belongsToMany[$name]);
                $value=$btm->pluck('id')->toArray();
                $htmlAttributes=utils::addArrayStrings($htmlAttributes,['multiple' => 'true']);
            }else{
                $value= $this->{$md["column_name"]};
            }
            $options=array_merge($md,$options);//2nd wins over 1st. Other keys such as column_name are ignored
            //if (\Auth::user()->privilege>=100) return $name.utils::viewStuff(["name"=>$name,"htmlAttributes"=>$htmlAttributes,"options"=>$options,"metadata"=>$md]);//DEBUG
            //CALL UNDERLYING METHODS:
            $result=SVL::input($md["dom_type"],$name,$value,$htmlAttributes,$options);
        } else{
            if (\Auth::user()->privilege>=100) $result=$this->shortClass (). ".$name: no metadata found";
        }
        return $result;
    }
    
    public function label($name,$value="",$htmlAttributes=array()){
        //label for field, with language based name, description as popup, class based on required
        $htmlAttributes=utils::addArrayStrings($htmlAttributes,['data-field'=>$name,"class"=>"sv_label"]);//added anyway
        $modelMd=self::metadata();
        if(isset($modelMd[$name])){//value and other attributes based on metadata:
            $md=$modelMd[$name];
            if(!$value)$value=utils::langDecode($md["name"]);//uf name            
            $htmlAttributes=  utils::addArrayStrings($htmlAttributes,
                ['class'=>(str_contains($md["rules"],"required")?config('SVLibs.form.required_class'):""),
                "data-toggle"=>"tooltip","title"=>$md["description"],"tabindex"=>-1]
            );             
        }
        return SVL::label($name, $value, $htmlAttributes);
    }
    
    //UTILITIES:
    
    public function debug(){
        $result=[];
        //$result["hierarchy"]=config("rdp.hierarchy");
        $result["data"]=$this->getAttributes();
        $result["metadata"]=self::metadata();
        $result["alttable"]=str_replace("app\\","",strtolower($this->getTable()));
        $result["table"]=$this->getTable();        
        return $result;
    }
    
    public static function metadata(){//loads from file and prepares attibutes metadata. Format:
        //metadata[attribute]=[column_name,dom_type,rules,list_source,value_source,name,description,decimals]
        if(!count(self::$metadataArray)){//once
            $lang=App::getLocale();        
            $header = true;
            $class=strtolower(str_replace("App\\","", static::class));
            $handle = fopen(storage_path().'/csv/_s_columns.csv', "r");  
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if ($header) {
                    $header = false;//visited once to skip
                } else {
                    if ($csvLine[0]==$class){//0 to select, 1 as a key; data start from 2
                        $line["column_name"]=$csvLine[2]?$csvLine[2]:$csvLine[1];//default is field name
                        $line["dom_type"]=$csvLine[3];//text|hidden|select|etc
                        $line["rules"]=$csvLine[4];//laravel basic standard rules string
                        $line["list_source"]=utils::langDecode($csvLine[5],$lang);//SELECT statement | json array.                         
                        $line["value_source"]=utils::langDecode($csvLine[6],$lang);                  
                        $line["name"]=utils::langDecode($csvLine[7],$lang);//user friendly name, possibly language based 
                        $line["description"]=utils::langDecode($csvLine[8],$lang);//user friendly description, possibly language based 
                        $line["decimals"]=$csvLine[9];//ignored if not numeric
                        self::$metadataArray[$csvLine[1]]=$line;//ADD TO MAIN RESULT WITH FIELD NAME AS KEY                                          
                    }
                }
            }
        }
        return self::$metadataArray;
    }
    
    public function shortClass(){//or get_class w/o the whole namespace
        $reflect=new \ReflectionClass($this);return $reflect->getShortName();
    }   
        
    public function name($name){
        $result=self::metadata()[$name]["name"];
    }
    
    public function permissions($user=NULL){
        return ["M"=>true,"D"=>true,"A"=>true];
    }
    
    public function readOnly(){//default: all table, current user
        return true;//TODO SVSV
    }                                 
    
    protected function exceptionMessage(\Exception $e, $input=NULL){
            $info=utils::exceptionInfo($e);
            //TODO CANCEL ALTOGETHER AFTER TESTS (26/9/17):
            //return $info["message"];//."<br>(".$info["original_message"].")";
            switch($e){
            case ($e instanceof \Illuminate\Database\QueryException)://SQL 
                $sql = $e->getSql();
                $bindings = $e->getBindings();
                // Process the query's SQL and parameters and create the exact query
                foreach ($bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $bindings[$i] = "'$binding'";
                        }
                    }
                }
                $query = str_replace(array('%', '?'), array('%%', '%s'), $sql);
                $query = vsprintf($query, $bindings);
                $errorInfo = $e->errorInfo;
                $output = [
                    'sql'        => $query,
                    'message'    => isset($errorInfo[2]) ? $errorInfo[2] : '',
                    'sql_state'  => $errorInfo[0],
                    'code' => $errorInfo[1]
                ];                
                $state_err=$errorInfo[0].".".$errorInfo[1]; //SQLSTATE.error_code
                $errsArray=config("SVLibs.sql_errors.".App::getLocale());
                if(array_key_exists($state_err,$errsArray)){
                    $message=$errsArray[$state_err];                
                }else{ // not mapped error - see https://dev.mysql.com/doc/refman/5.5/en/error-messages-server.html
                    $message="SQL ERROR - query: " .$query." | message: ". (isset($errorInfo[2]) ? $errorInfo[2] : '') . " | state.err:". $state_err;
                    if($input!=NULL) $message.="input array:<br>".utils::viewStuff ($input);
                }
                return $message;
                //return redirect()->back()->withErrors(["message"=>$message]);
                //return redirect()->back()->withErrors($validator)->withInput();
                break;
            case($e instanceof \InvalidArgumentException):
                return "invalid argument exception";
                break;
            default:
                return get_class($e) ."\n error " . $e->getCode() . "\n " . $e->getMessage() . "\n\n in " . $e->getFile() . ", line ". $e->getLine();
            }
        }   
           
}
