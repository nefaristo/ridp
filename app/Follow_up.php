<?php

namespace App;

use App\RDPModel;
use Illuminate\Support\Facades\App; //for language
use Carbon\Carbon; 
use App\SVLibs\utils; 
use App\Treatment;
use Illuminate\Support\Facades\DB;
use App\Therapy;

class Follow_up extends RDPModel {
     
    public static $parentClass = 'Treatment';
    protected $guarded = ['id'];
    protected $dates=['date'];
    
    //overriden

    public function title() {
        return ($this->time!==NULL && $this->time!=="") ? self::timeDescription($this->time) : utils::date2string($this->date);
    }

    public function input($name,$htmlAttributes=[],$options=[]) {//partial override:
        if ($name=="time") $options["list_source"]=$this->viableTimesList();
        return parent::input($name,$htmlAttributes,$options);
    }
    
    protected function parentType(){
        $parent=$this->parentModel();
        return $parent?$parent->type:NULL;       
    }

    public function check($op="M",$input=NULL) {
        $result = parent::check($op,$input);
        $this->fill($input);
        $this->mergeInfo($this->parentModel()->checkChildren($this));
        //check date-time consistency:       
        if ($op=="M" && $this->time != "" && $this->date) {//if there are both
            $time = $this->time;
            $date = utils::carbonDate($this->date); //time is for "use" afterwards
            //take the expected date from the array returned by the utility followUps of the parent treatment:
            $timeDateInfo = array_filter($this->parentModel()->followUps(), function($v)use($time) {
                return $v["time"] == $time;
            });
            if (count($timeDateInfo) > 0) {//if a filtered array is there...
                if (abs($date->diffInDays(current($timeDateInfo)["date"])) >
                        config("rdp.misc.follow_ups.tolerance_days")) { //...and actual date-expected date>tolerance,
                    $this->errors->add("date", trans("rdp.errors.followup_datetimeconsistency"));
                }
            }
        }
        return $this->errors;
    }

    //custom methods

    public function children($add = false) {
        //array of models/collections of 4 types of children:
        $result = ["clinical" => NULL, "biochemical" => NULL, "prescription" => NULL, "therapy" => NULL];
        if ($this->id && $this->type != "TX") {
            $parentType = strtolower($this->parentModel()->type);//treatment type
            foreach ($result as $key => $value) {//loop on children type
                $class = '\\App\\' . ucfirst(($key == "prescription") ? $parentType . "_" . $key : $key);
                $current = $class::where("parent", "=", $this->id);
                if ($key == "therapy") {
                    $current = $current->get(); //the whole collection
                } else {
                    $current = $current->first(); //only the first one,and 
                    if ($add && !$current) {//adds automatically if asked and it's not present:
                        $new = new $class($this->id);
                        $current = $new->checkAndUpdate("A");
                        $current = $new;
                    }
                }
                $result[$key] = $current;
            }
        }
        return $result;
    }
    public function copyTherapies($from=NULL){//default: copies from last follow up overwriting existing therapies
       // return $this->attributesToArray() ;
        //$maxLastDate=DB::select("SELECT count(*) AS N FROM follow_ups WHERE parent=? AND date<? GROUP BY parent ",[$this->parent,$this->date]);
        //maxLastDate=Follow_up::select("max(date)")->groupBy("parent")->having("parent","=",$this->parent)->get();
        $result=["message"=>"","id"=>NULL,"date"=>NULL,"n"=>0,"records"=>[]];
        $maxLastDate=DB::select("SELECT max(date) AS maxDate FROM follow_ups WHERE parent=? AND date<? GROUP BY parent ",[$this->parent,$this->date]);
        if(count($maxLastDate)>0){
            $previous= Follow_up::where("parent","=",$this->parent)->where("date","=",$maxLastDate[0]->maxDate)->first();
        }
        if($previous){
            $result["id"]=$previous->id;$result["date"]=$previous->date;
            $sources=Therapy::where("parent","=",$previous->id)->get();
            $result["n"]=count($sources);
            if($result["n"]>0){
                foreach ($sources as $source){                    
                    $target= Therapy::where("parent","=",$this->id)->where("type","=",$source->type)->first();                    
                    if(!$target){
                        $target = $source->replicate();$target->parent=$this->id;$target->push();
                    }
                    $result["records"][$source->id]=["source"=>$source->attributesToArray(),"target"=>$target];
                }
            }   
            $result["message"]=trans("rdp.copied").": ".$result["n"];
        }
        return $result;
        return utils::viewStuff($result,1);
    }
    private function viableTimesList($userFriendly = true) {//including this model's one. if userFriendly=false returns the number
        $result = ["" => ($userFriendly ? "-" : "")];
        $parent = $this->parentModel();
        if ($parent) {
            $viable = array_filter($parent->followUps(), function($el) {
                return $el["time"] === $this->time || ($el["info"] == "X" && !($el["model"]));
            }); //this time or the allowed+not set           
            foreach ($viable as $viablEl) {
                $time = $viablEl["time"];
                $result[$time] = $userFriendly ? ($this->timeDescription($time)) : $time;
            }
        }
        return $result;
    }

    private static function timeDescription($time) {
        static $desc=[
            0=>["en"=>"treatment start","it"=>"inizio trattamento"],
            -1=>["en"=>"treatment end","it"=>"fine trattamento"],
            1=>["en"=>"month","it"=>"mese"],
        ];
        if($time===NULL) return "";
        $lang = App::getLocale();        
        return ($time>0)?($desc[1][$lang]." " .$time):($desc[$time][$lang]);        
    }
}