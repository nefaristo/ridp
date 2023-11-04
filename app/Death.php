<?php
namespace App;

use App\RDPModel;

class Death extends RDPModel
{
    protected $table='deaths';
    public static $parentClass='Patient';
    protected $guarded=['id'];
    protected $dates=['date'];
    public function title(){
        return \App\SVLibs\utils::date2string($this->date);
    }
    public function check($op="M",$input=NULL){
        $result=parent::check($op,$input);
        $this->fill($input);
        $result->merge($this->parentModel()->checkPatientDeathTreatments(NULL,$this));
        return $result;
    }     
}
