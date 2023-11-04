<?php
namespace App;

use App\RDPModel;

class Catheter extends RDPModel
{
    protected $table='catheters';    
    public static $parentClass='Treatment';    
    protected $guarded=['id'];
    protected $dates=['date','removal_date'];
    
    public function title(){
        return \App\SVLibs\utils::date2string($this->date);
    }   
    
    
    public function check($op="M",$input=NULL){
        parent::check($op,$input);
        if($op!="D"){
            if($this->removal_date){
                if($this->removal_date<$this->date) $this->errors->add("removal_date",trans("rdp.errors.catheter_removal_dates"));
                if(! $this->removal_reason) $this->errors->add("removal_date",trans("rdp.errors.catheter_removal_reason_missing"));                
            }            
            $this->errors->merge($this->checkChildren()); //catheter complications and medications 
            $this->mergeInfo($this->parentModel()->checkChildren($this));
        }
        return $this->errors;
    }   

    public function checkChildren($child=NULL){
        //dates consistencies; child==NULL <=> treatment is being updated
        $result= new \Illuminate\Support\MessageBag;
        $errorClass=[
            "App\Catheter_complication"=>trans("rdp.errors.catheter_complications_date"),
            "App\Catheter_medication"=>trans("rdp.errors.catheter_medications_date")];        
        if($child!=NULL){//single child calling for confirmation: check one condition on one record only
            if($this->date && $child->date && $this->date>$child->date)
                $result->add("date",$errorClass[get_class($child)]);
        }else{//catheter update: check every record of every child table
            foreach($errorClass as $class=>$error){//loop on children type
                foreach($class::where("parent","=",$this->id)->get() as $child){//loop on records
                    if($this->date>$child->date)
                        $result->add("date", $error);                        
                }
            }
        }
        return $result;
    }    
    
}
