<?php

namespace App;
use App\RDPModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

//use Illuminate\Database\Eloquent\Model;
 
class Center extends RDPModel 
{ 
    protected $table="centers";
    protected $guarded=['id'];
    
    //public static $parentClass=NULL; already in RDPModel
    
    public function title(){
        return $this->unit. " ".$this->institute. " ". $this->city;
    }
    public function scopeBaseview ($query){
        return $query->from('centers');
    }   
    public function permissions($user=NULL){//not deletable anyway:
        if ($user==NULL) $user=Auth::user();
        $result["M"]=($this->id==$user->center);
        $result["A"]=false;$result["D"]=false;
        return $result; 
    }    
    
    public function patients(){
        return $this->hasMany("App\Patient","parent","id");
    }
    
    public function centerInfo(){//general info: patients etc TODO from SQL to laravel querying?
        $result=[];
        $patients= $this->patients();
        $result[trans("rdp.patients")]=$patients->count();
        $activePatients= DB::select("SELECT COUNT(id) AS N FROM `patients_treatments_` WHERE treats_open>0 AND parent=?",[$this->id]);        
        $notUpdatePatients= DB::select("SELECT COUNT(id) AS N FROM `patients_treatments_` WHERE treats_open>0 AND parent=? AND last_complete_update<DATE_SUB(NOW(),INTERVAL 1 YEAR)",[$this->id]);
        $result[trans("rdp.patients_under_treatment")]=count($activePatients)?$activePatients[0]->N:0;
        $result[trans("rdp.patients_not_updated")]=count($notUpdatePatients)?$notUpdatePatients[0]->N:0;
                //where('active',"=",true)->count();
        //$result["test"]=$this->whereHas('patients',function($q){$q->where("active",1);})->count();        
        
        return $result;        
    }
}
