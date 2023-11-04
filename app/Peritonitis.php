<?php
namespace App;

use App\RDPModel;
use App\SVLibs\utils; //for date overlap & c;

class Peritonitis extends RDPModel 
{
    protected $table='peritonites';    
    public static $parentClass='Treatment';   
    protected $guarded=['id','diagnoses'];
    protected $belongsToMany=["diagnoses"=>['\App\_l_peritonitis_diagnosis','peritonites_diagnoses','peritonitis_id','peritonitis_diagnosis_id']];
    protected $dates=['date','date_peritoneal_liquid_culture_1','date_peritoneal_liquid_culture_2'];
    
    public function title(){return \App\SVLibs\utils::date2string($this->date);}
    
    public function check($op="M",$input=NULL){
        parent::check($op,$input);
        if($op!="D"){
            //CULTURE EXECUTION: 
            //at least one date+germ or reason for no culture
            if(!(
              ($this->date_peritoneal_liquid_culture_1 && $this->germ_peritoneal_liquid_culture_1) 
              ||($this->date_peritoneal_liquid_culture_1 && $this->germ_peritoneal_liquid_culture_2)
              ||$this->non_culture_execution_reason)){
                $this->errors->add("date_peritoneal_liquid_culture_1",trans("rdp.errors.perit_culture_execution"));
            }
            //dates:
            if($this->date_peritoneal_liquid_culture_1 && $this->date_peritoneal_liquid_culture_1<$this->date) $this->errors->add("date_peritoneal_liquid_culture_1",trans("rdp.errors.perit_culture_date"));
            if($this->date_peritoneal_liquid_culture_2 && $this->date_peritoneal_liquid_culture_2<$this->date) $this->errors->add("date_peritoneal_liquid_culture_2",trans("rdp.errors.perit_culture_date"));
            //HIERARCHY RELATED:
            $this->errors->merge($this->checkChildren());   
            $this->mergeInfo($this->parentModel()->checkChildren($this));
        }
        return $this->errors;
    }   
    
    public function checkChildren($child=NULL){
        //dates consistencies; child==NULL <=> treatment is being updated
        $result=new \Illuminate\Support\MessageBag();
        $peritRange=[NULL,$this->date];   
        if($child){//single child calling for confirmation: check one condition on one record only
            if($child->start_date && utils::overlap($peritRange,[$child->start_date,$child->end_date],true))
                $result->add("start_date",trans("rdp.errors.perit_therapy_date"));
        }else{//perit update: check every record of every child table
            foreach(\App\Peritonitis_therapy::where("parent","=",$this->id)->get() as $child){            
                if(utils::overlap($peritRange, [$child->start_date,$child->end_date],true))
                    $result->add("start_date", trans("rdp.errors.perit_therapy_date"));                        
            }
        }
        return $result;
    }
}
