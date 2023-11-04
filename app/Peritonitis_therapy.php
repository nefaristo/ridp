<?php
namespace App;

use App\RDPModel;
use Illuminate\Support\Facades\DB;

class Peritonitis_therapy extends RDPModel
{
    protected $table='peritonitis_therapies';
    
    public static $parentClass='Peritonitis';
    
    protected $guarded=['id'];
    
    public function unit($via=NULL){
        if(!$via)$via=$this->administration_via;
        $v=DB::table('_l_drug_administration_via')->where("id","=",$via)->first();
        if($v){return $v->unit;}else{return "-";}
    }
            
    public function check($op="M",$input=NULL){
        $result=parent::check($op,$input);
        if($this->parentModel())$result->merge($this->parentModel()->checkChildren($this));
        if(!$this->drug>0) $result->add("drug",trans("rdp.errors.required_field"));
        if($this->end_date && $this->start_date && $this->end_date<=$this->start_date) $result->add("start_date",trans("rdp.errors.perit_therapy_fromto"));
        return $result;
    }  
    
}
