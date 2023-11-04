<?php
namespace App;

use App\RDPModel;

class Complication extends RDPModel
{
    protected $table='complications';
    
    public static $parentClass='Treatment';
    
    protected $guarded=['id'];
    protected $dates=['date'];
    
    public function scopeBaseview ($query){
        return parent::scopeBaseview($query)->orderBy("date");        
    }//DEFAULT: raw table
    
    public function check($op="M",$input=NULL){
        parent::check($op,$input);
        $this->fill($input);
        $this->mergeInfo($this->parentModel()->checkChildren($this));
        return $this->errors;
    }     
}
