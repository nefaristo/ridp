<?php
namespace App;

use App\RDPModel;

class Pd_connection extends RDPModel
{
    protected $table='pd_connections';    
    public static $parentClass='Treatment';    
    protected $guarded=['id'];
    protected $dates=['date'];
    
    public function title(){
        return \App\SVLibs\utils::date2string($this->date);
    }
    
    public function check($op="M",$input=NULL){
        parent::check($op,$input);
        $this->fill($input);
        $this->mergeInfo($this->parentModel()->checkChildren($this));
        return $this->errors;
    }
}
