<?php
namespace App;
 
use App\RDPModel;  
use Carbon\Carbon;
use App\SVLibs\utils; 
use Illuminate\Contracts\Support\MessageBag;

class Treatment extends RDPModel
{
    public static $parentClass='Patient';
    
    protected $guarded=['id']; 
    protected $dates=['start_date','end_date'];
    //arrays for non-DB based select lists:
    private $typesArray=[
        "it"=>[""=>"","PD"=>"peritoneale","HD"=>"emodialisi","TX"=>"tx preemptive"],
        "en"=>[""=>"","PD"=>"peritoneal","HD"=>"hemodialysis","TX"=>"preemptive tx"],
    ];
    private $formerTreatmentsArray=[
        "it"=>[""=>"-","CT"=>"terapia conservativa","PD"=>"dialisi peritoneale","HD"=>"emodialisi","TX"=>"trapianto"],
        "en"=>[""=>"-","CT"=>"conservative therapy","PD"=>"peritoneal dialysis","HD"=>"hemodialysis","TX"=>"transplantation"],    
    ];
    private $formerTreatmentsAllowedArray=[//"" is here not to suggest any default but wouldn't be allowed in validation
        "PD"=>["","CT","HD","TX"],
        "HD"=>["","CT","PD","TX"],
        "TX"=>["","CT"],
    ];
    private $txFromArray=[
        "it"=>[""=>"-","LD"=>"vivente","DD"=>"cadavere"],
        "en"=>[""=>"-","LD"=>"living donor","DD"=>"dead donor"],
    ];    
    
    private $childrenToCheck=[//[date_field=>field_name to check,"treatment_type"=>treatment type it should be
        "App\Follow_up"=>["date_field"=>"date","treatment_type"=>["PD","HD"]],
        "App\Pd_connection"=>["date_fieldDISABLED"=>"date","treatment_type"=>["PD"]],
        "App\Hd_access"=>["date_fieldDISABLED"=>"date","treatment_type"=>["HD"]],
        "App\Catheter"=>["treatment_type"=>["PD"]],
        "App\Peritoneal_equilibration_test"=>["date_field"=>"date","treatment_type"=>["PD"]],
        "App\Peritonitis"=>["date_field"=>"date","treatment_type"=>["PD"]],
        "App\Complication"=>["date_field"=>"date"],
    ];
    
    //overriden
        
    public function title(){
        if($this->id!=null){
            return $this->typesArray[\App::getLocale()][$this->type] . " " . utils::date2string($this->start_date) .(($this->end_date)? "-" . utils::date2string($this->end_date):"");        
        }else{
            return "";
        }
    }
    
    public function input($name,$htmlAttributes=[],$options=[]){
        if ($this->readOnly()) $htmlAttributes=utils::addArrayStrings ($htmlAttributes, ["disabled"=>"disabled"]);                        
        switch($name){
        case "type":
            $options["list_source"]=$this->availableTypesList();
            break;
        case "former_treatment":
            $options["list_source"]=$this->formerTreatmentsList();
            break;
        case "end_cause":
            $options["list_source"]=$this->endCauseList();
            break;
        case "tx_from":
            $options["list_source"]=$this->txFromList();
            break;            
        }  
        return parent::input($name,$htmlAttributes,$options);
    }
       
    public function label($name, $value = NULL, $htmlAttributes = array()) {
        switch($name){
            case "tx_failure_cause":
                return parent::label($name, $value, utils::addArrayStrings(["class"=>"sv_required"],$htmlAttributes));
            default:
                return parent::label($name, $value, $htmlAttributes);
        }
    }
    
    public function check($op="M",$input=NULL){
        parent::check($op,$input);
        if($op!="D"){ 
            if(!$this->former_treatment) $this->errors->add("former_treatment",trans("rdp.errors.required_field"));
            if(!$this->treatmentTypeUpdatable() && $this->getOriginal("type")!=$this->type)
                $this->errors->add("type","type not updatable");//redundant with type list: this shouldn't show        
            if($this->end_date  && !$this->end_cause) $this->errors->add("end_cause",trans("rdp.errors.treat_missing_end_cause"));//end=>end cause needed (standard laravel validator not working)
            switch($this->end_cause){
            case config("rdp.misc.treatment_end_cause.death"):
                $postDeathTreats=
                    \App\Treatment::where("id","!=",$this->id)->where("parent","=",$this->parent)//siblings
                    ->where(function($query){$query->where("end_date",">=",$this->end_date)->orWhere("end_date","=",NULL);})//w/o end or w/ end > death
                        ->get();
                $postDeathTreatsString="";
                foreach($postDeathTreats as $rec) $postDeathTreatsString.= "<br>".$rec->title();
                if($postDeathTreatsString) $this->errors->add(ALLFIELDS_,trans("rdp.errors.death_treatments").$postDeathTreatsString);
                break;
            case config("rdp.misc.treatment_end_cause.tx_failure"):
                if(!$this->tx_failure_cause) $this->errors->add("tx_failure_cause",trans("rdp.errors.tx_failure_cause_required"));
                break;
            }
            if($this->errors->isEmpty()){//some redundancies, ie empty date=treatments overlapping etc
                $this->errors->merge($this->parentModel()->checkPatientDeathTreatments($this));  
                $this->errors->merge($this->checkSiblings());
                $this->mergeInfo($this->checkChildren());
            }
        }
        return $this->errors;
    }   
       
    public function checkAndUpdate($op = "S", $input = NULL) {
        $result=parent::checkAndUpdate($op, $input); 
        //death update after saving:
        if($result && $this->parentModel()) $this->parentModel()->deathAdjust();//automatic Patient->death add/delete  
        return $this->errors;
    }   
    
    //custom checks and validations (on model attributes=>requires fill first)
    
    private function checkSiblings(){
        $errors= new \Illuminate\Support\MessageBag();
        $thisPeriod=[utils::carbonDate($this->start_date),utils::carbonDate($this->end_date)];        
        foreach($this->siblings()->get() as $sibling){
            if(utils::overlap($thisPeriod,[utils::carbonDate($sibling->start_date),utils::carbonDate($sibling->end_date)],true)){
                $errors->add("start_date",trans("rdp.errors.treat_overlapping_treatments").":<br>"
                . $sibling->type. " " .utils::date2string(utils::carbonDate($sibling->start_date))." - ".utils::date2string(utils::carbonDate($sibling->end_date)));
            }
        }
        return $errors;
    }
          
    public function checkChildren($child=NULL){
        //dates and treatment type consistencies
        //child==NULL <=> treatment is being updated
        $result=[];
        $result["errors"]=new \Illuminate\Support\MessageBag;$result["warnings"]=new \Illuminate\Support\MessageBag;$result["messages"]=new \Illuminate\Support\MessageBag;
        $treatRange=[$this->start_date,$this->end_date];
        $children=$this->childrenToCheck;  
        if($child){//single child calling for confirmation: check on one record only
            //TYPE: 
            $childClass=get_class($child);
            if(array_key_exists("treatment_type",$children[$childClass]))//treatment type is there so it's to be checked
                if(!in_array($this->type,$children[$childClass]["treatment_type"]))//if the current treatment type is not present,
                    $result["errors"]->add("type",trans("rdp.errors.treat_non_matching_type")." (".$this->type.",". $child->formTitle().")");//adds a non matching type error
            //DATE:                                    
            if(key_exists("date_field",$children[$childClass])){  
                $dateField=$children[$childClass]["date_field"];
                if(!utils::overlap($treatRange,utils::carbonDate($child->$dateField))) 
                    $result["errors"]->add($dateField,trans("rdp.errors.treat_non_overlapping_date") . " (" . $this->title(). ")");
            }  
            //CUSTOM:
            switch($childClass){                
                case "App\Catheter"://NO ERROR (by request); warning for insertion preceding treatment by more then x days (30 now)
                    $warningTolerance=config("rdp.misc.catheter_date_tolerance_days_warning");
                    if(utils::carbonDate($child->date)->addDays($warningTolerance)<$this->start_date){
                            $result["warnings"]->add("date",str_replace("{days}",$warningTolerance,trans("rdp.warnings.catheter_insertion_date_to_treatment")) . " (" . $this->title(). ")");
                    }   
                    //$result["warnings"]->add("date",str_replace("{days}",$tolerance,trans("rdp.warnings.catheter_insertion_date_to_treatment")) . " (" . $this->title(). ")");
                    break;               
            }
        }else{//treatment update: check every record of every child table 
            foreach($children as $className =>$value){
                $recs=$className::where("parent","=",$this->id)->get();             
                foreach($recs as $rec){  
                    //TYPE:
                    if(array_key_exists("treatment_type",$value))//treat type is in the to-be-checked array=>check
                        if(!in_array($this->type,$value["treatment_type"]))//treat type not matching
                            $result["errors"]->add("type",trans("rdp.errors.treat_non_matching_type")." (".$this->type.",".$child->formTitle().")");//adds a non matching type error    
                    //DATE:
                    if(isset($value["date_field"]) && !utils::overlap($treatRange, $rec->{$value["date_field"]})){//DATE
                        $result["errors"]->add(ALLFIELDS_,$rec->formTitle(). " (". SVLibs\utils::date2string($rec->{$value["date_field"]}) . "): ".trans("rdp.errors.treat_non_overlapping_date"));
                    }
                }
            }
        }
        return $result;
    }   
    
    // custom select list
    
    private function availableTypesList($lang=null){//quick common language specific types array        
        if($lang===NULL)$lang=\App::getLocale();
        $result=$this->typesArray[$lang];//limits to current language
        if(!$this->treatmentTypeUpdatable()){$result=[$this->type=>$result[$this->type]];}//limits to current type...no change possible
        return $result;
    }
    
    private function formerTreatmentsList($lang=NULL){
        if($lang===NULL)$lang=\App::getLocale();    
        if($this->type){
            $allowed=[];
            foreach($this->formerTreatmentsAllowedArray[$this->type] as $v){
                $allowed[$v]=$this->formerTreatmentsArray[$lang][$v];
            }
            return $allowed;
        }else{
            return ["","-"];
        }
    }
    
    private function txFromList($lang=NULL){
        if($lang==NULL)$lang=\App::getLocale();
        return $this->txFromArray[$lang];//limits to current language
    }
    
    private function orderAmongSiblings(){
        return 1 + Treatment::where("parent","=",$this->parent)->where("start_date","<=",$this->start_date?$this->start_date:"start_date")->count();//ugly but compact way of dealing with empty $this->start_date (before saving a new record)
    }
    
    private function endCauseList($lang=NULL){
        if($lang===NULL)$lang=\App::getLocale();
        $result= utils::listOptions("SELECT id,name_{$lang} FROM _l_treatment_end_causes WHERE treatment_type LIKE '%".($this->id?$this->type:"dummy")."%' ORDER BY id");
        if ($this->orderAmongSiblings()<Treatment::where("parent","=",$this->parent)->count()){
            $deathCode=config("rdp.misc.treatment_end_cause.death");
            if(isset($result[$deathCode]))unset($result[$deathCode]);
        }
        return $result;
    }
    
    //utilities:
    
    private function treatmentTypeUpdatable(){
        $result=true;
        if($this->id!==NULL){
            foreach($this->childrenToCheck as $k=>$v)
                if($k::where("parent","=",$this->id)->count()>0) $result=false;
        };
        return $result;
    }
    
    public function followUps(){ 
        //MERGE OF ACTUAL RECORDS WITH EXPECTED ONES, so that
        //time!=NULL<=>record should be there, model!=NULL<=>record is there
        //format:[
        //"time"=>theo[=actual of any] time|NULL (if free f.u.),
        //"date"=>theorical date|NULL (free f.u.), (real date is within model if any)
        //"model"=>followupmodel|NULL (if not existing),
        //"info"=>  * = due & correct follow up (expected,present and in range or range N/A)
        //          F = free added (no date check)
        //          X = expected, present or not (check with model)
        //          U = unexpected, present 
        //          D = expected & present, date out of range
        
        $result=[];
        if($this->id){
            //0) sets data...
            $step=config("rdp.misc.follow_ups.step_months");
            $tolerance=config("rdp.misc.follow_ups.tolerance_days");            
            $start_date=Carbon::parse($this->start_date);
            $end_date=($this->end_date)?Carbon::parse($this->end_date):Carbon::now();
            //1) ARRAY OF EXPECTED FOLLOWUPS ONLY:
            if($this->type!="TX"){//preemptive tx doesn't expect follow up
                //1a) END treatment FU, if needed:
                if($this->end_date){
                    $rec=\App\Follow_up::where("parent",$this->id)->where("time",-1)->first();
                    $result[]=["time"=>-1,"date"=>$end_date,"model"=>$rec, "info"=>($rec?"*":"X")];
                }
                //1a) standard FUs WITHIN treatment: 
                for($i=0;$i<=$end_date->diffInMonths($start_date);$i+=$step){
                    $expdate=$start_date->copy()->addMonths($i);                
                    //adds if: 0 OR open treatment OR too close to end:
                    if($i==0 || !$this->end_date || abs($end_date->diffInMonths($expdate))>$tolerance)
                        $rec=\App\Follow_up::where("parent",$this->id)->where("time",$i)->first();
                        //if($rec->count()==0)$rec=null;
                    if(count($rec)){
                        $actualDate=utils::carbonDate($rec->date);
                        //CHECK THE DATE OF EXPECTED+PRESENT: X=ok,D=out of range                            
                        $info=(abs($actualDate->diffInDays($expdate))<=$tolerance)?"X":"D";
                    }else{
                        $info="X";
                    }
                    $result[]=["time"=>$i,"date"=>$expdate,"model"=>$rec,"info"=>$info]; 
                } 
            }
            //2) REMAINING ARRAY FROM ACTUAL RECORDS:
            $records=\App\Follow_up::where("parent",$this->id)->orderby("date")->get();
            foreach($records as $rec){//loop on all actual records:
                if($rec->time){//time present=>record is supposed to be among expected:
                    if(!array_filter($result,function($resEl) use($rec) {return $resEl["time"]===$rec->time;})){//exptected filtered empty, it's not among expected
                        $result[]=["time"=>$rec->time,"date"=>NULL,"model"=>$rec,"info"=>"U"];
                    }
                }else{//time not present=> "free" extra follow up:
                    $result[]=["time"=>NULL,"date"=>NULL,"model"=>$rec,"info"=>"F"];
                }                
            }
            //3)CHECK that date is within time range:
            
        }
        return $result;        
    }
    
}
