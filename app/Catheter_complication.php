<?php
namespace App;

use App\RDPModel;

class Catheter_complication extends RDPModel
{
    protected $table='catheter_complications';
    
    public static $parentClass='Catheter';
    
    protected $guarded=['id','symptoms'];
    
    protected $belongsToMany=['symptoms'=>['\App\_l_catheter_complication_symptom','catheter_complications_symptoms','catheter_complication_id','symptom_id']];
            
    public function title(){return \App\SVLibs\utils::date2string($this->date);}
    
    public function check($op="M",$input=NULL){
        $result=parent::check($op,$input);
        $this->fill($input);
        if($op!="D"){
            $result->merge($this->parentModel()->checkChildren($this));
            if(!$this->type>0) $result->add("type",trans("rdp.errors.required_field"));        
        }
        return $result;
    }   
    
    public function checkAndUpdate($op = "M", $input = NULL) {
        $result=parent::checkAndUpdate($op, $input);
        return $result;
    }      
        
    public function symptoms(){
        return $this->belongsToMany('\App\_l_catheter_complication_symptom','catheter_complications_symptoms','catheter_complication_id','symptom_id');
    }
    
}
