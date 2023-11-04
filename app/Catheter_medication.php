<?php
namespace App;

use App\RDPModel;

class Catheter_medication extends RDPModel
{
    protected $table='catheter_medications';    
    public static $parentClass='Catheter';    
    protected $guarded=['id'];
    protected $dates=['date'];
    
    public function title(){return \App\SVLibs\utils::date2string($this->date);}
    
    public function check($op="M",$input=NULL){
        $result=parent::check($op,$input);
        if($op!="D"){
            $result->merge($this->parentModel()->checkChildren($this));            
        }        
        return $result;
    }      
}
