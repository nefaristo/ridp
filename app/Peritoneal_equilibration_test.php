<?php
namespace App;

use App\RDPModel;

class Peritoneal_equilibration_test extends RDPModel
{
    protected $table='peritoneal_equilibration_tests';
    public static $parentClass='Treatment';
    protected $guarded=['id'];
    protected $dates=['date'];
    
    public function title(){
        return \App\SVLibs\utils::date2string($this->date);
    }
    
    public function check($op="M",$input=NULL){
        parent::check($op,$input);
        if($this->parentModel()) $this->mergeInfo($this->parentModel()->checkChildren($this));
        return $this->errors;
    }    
}
