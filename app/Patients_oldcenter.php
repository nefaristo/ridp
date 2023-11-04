<?php
namespace App;

use App\RDPModel;
use Carbon\Carbon;
use Validator;

class Patients_oldcenter extends RDPModel
{
    public static $parentClass='Patient';
    protected $table='patients_oldcenters';
    protected $guarded=['id'];
    protected $dates=['end_date'];
       /*
    public function parentModel(){//not standard
        $parentNSClass='\App\\'.$this::$parentClass;//namespaced class
        if($this->parent){
            $result= $parentNSClass::where("id","=",$this->patient_id)->get();
        }else{
            $result= NULL;
        }
        $result= ($result!=NULL)?$result->first():NULL;
        return $result;
    } */
    
    public function check($op="M",$input=NULL) {
        parent::check($op,$input);
        if($op!="D"){
            if(!$this->center_id && ! $this->other_center) $this->errors->add("center_id",$this->end_date. " ".trans("rdp.errors.oldcenters_required"));
            if($this->end_date >Carbon::today()) $this->errors->add("end_date",trans("rdp.errors.only_past_date"));
        }
        return ($this->errors->isEmpty());
    }
    
    public function checkAndUpdate($op = "M", $input = NULL) {
        parent::checkAndUpdate($op, $input);
        return ($this->errors->isEmpty());
    }
}
