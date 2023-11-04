<?php
namespace App;

use App\RDPModel;
use App\SVLibs\SVL;//for input
use App\SVLibs\utils;//for overlapping dates etc
use App\Patients_oldcenter;
use Carbon\Carbon;//for overlapping dates etc
use Collective\Html\FormFacade as Form;

class Patient extends RDPModel
{
    public static $parentClass='Center';
    protected $guarded=['id','comorbidities','patoldcenter_id','patient_id','center_id','other_center','end_date'];
    protected $dates=['birth_date','last_complete_update'];
    protected $belongsToMany=["comorbidities"=>['\App\_l_comorbidity','patients_comorbidities','patient_id','comorbidity_id']];
    protected $appends = ['active'];//custom attribute
            
    //OVERRIDES:
    
    public function title(){return $this->code;}
    
    public function input($name,$htmlAttributes=[],$options=[]){
        if ($this->readOnly()) $htmlAttributes=utils::addArrayStrings ($htmlAttributes, ["disabled"=>"disabled"]);
        switch($name){
            case "code"://readonly
                $htmlAttributes=utils::addArrayStrings ($htmlAttributes, ["readonly"=>"readonly"]);
                return parent::input($name,$htmlAttributes,$options);
            case "parent"://if center, only one item in the list (previous if compiled, this center if not)
                //return ";"utils::viewStuff(session("center"));                
                if(session("center")){
                    $idCenter=$this->parent?$this->parent:session("center");     
                    return SVL::input("select", $name, 
                        $this->parent?$this->parent:session("center"), //id center, 
                        $htmlAttributes, 
                        ["list_source"=>"select id,centers_description from centers_ WHERE id=".$idCenter]
                    );
                }
                break;            
            default:
        }  
        return parent::input($name,$htmlAttributes,$options);
    }
    public function permissions($user = NULL) {
        $result= parent::permissions($user);
        if(!$this->id) $result["A"]=true;
        return $result;
    }
    public function oldCenters(){
        return Patients_oldcenter::where("patient_id","=",$this->id)->get();
    }      
    
    //utilities:
    public function getActiveAttribute(){
        return TRUE;//\App\Treatment::where("parent",$this->id)->where("end_date",NULL)->count()>0; 
    }
    
    //checking/updating:
    
    public function check($op="M",$input=NULL){
        parent::check($op,$input); 
        if($op!="D"){//save & add  
            //treatments and death consistency: 
            $this->checkPatientDeathTreatments(); 
            //future dates:
            if ($this->last_complete_update){
                if($this->last_complete_update> \Carbon\Carbon::now()) 
                    $this->errors->add("last_complete_update",  trans('rdp.errors.future_date'));
            }
        }else{               
            //gracefully intercepts the ON DELETE CONSTRAIN SQL error)
            if(\App\Treatment::where("parent","=",$this->id)->count()>0){
                $this->errors->add(ALLFIELDS_,trans('rdp.errors.treatments_present'));
            }  
        }        
        return $this->errors;
    } 
    
    public function checkAndUpdate($op = "M", $input = NULL) {
        $this->check($op,$input);//before, to seek for errors 
        //new patient=>warns about code: 
        $newCodeWarn=$this->id?"":(str_replace('{code}', $this->code, trans("rdp.warnings.newly_added_patient")));    
        parent::checkAndUpdate($op,$input);//resets err&war, so warnings afterwards
        if($this->errors->count()==0 && $newCodeWarn)$this->warnings->add("code",$newCodeWarn);
        
        //COMORBIDITIES:  
        //if($this->errors->count()==0 && isset($input["comorbidities"])) $this->comorbidities()->sync($input["comorbidities"]);       
        return $this->errors;
    }
       
    public function checkPatientDeathTreatments($treatment=NULL,$death=NULL){
        //$this patient is always a valid model; 
        //dates are checked before by standard validators, but they are rechecked (NULL in carbon doesn't give error)
        //BIRTH AND DATE DEATH: determine and check 
        $errors=new \Illuminate\Support\MessageBag;
        $birthDate=utils::carbonDate($this->birth_date);
        if(!$death){$death=\App\Death::where("parent","=",$this->id)->first();}//if not provided, search for a death model
        $deathDate=NULL;if($death){
            $deathDate=utils::carbonDate($death->date);
            if($deathDate<$birthDate) $errors->add(ALLFIELDS_,trans("rdp.errors.birth_death_order"));//NB: NULL<birth
        }
        //TREATMENT[s]: if not defined they're ALL called
        if(!$treatment){//null=>recursive on all treatments
            $treatments=\App\Treatment::where("parent","=",$this->id)->get();
            foreach($treatments as $treatment){
                $this->checkPatientDeathTreatments($treatment,$death);
            }
        }else{//core: single treatment present, birth and death valid
            $treatRange=[utils::carbonDate($treatment->start_date),utils::carbonDate($treatment->end_date)];
            if(utils::overlap($treatRange,$birthDate,true))//check birthdate is not within
                $errors->add(ALLFIELDS_,trans("rdp.errors.birth_treatments"));
            if($deathDate!=NULL && utils::overlap($treatRange,$deathDate,true))//check death date (separated errors just for being polite...)
                $errors->add(ALLFIELDS_,trans("rdp.errors.death_treatments"));
        }                  
        return $errors;        
    }        

    //others:

    public function deathAdjust(){//corrects consistency in death status:
        //end_cause==3 <=> parent has one and only one death form.
        //returns NULL or the death model itself. Used by treatment.        
        $deaths=\App\Death::where("parent",$this->id)->get();
        $treatEndByDeath=\App\Treatment::where("parent",$this->id)->where("end_cause",config("rdp.misc.treatment_end_cause.death"))->get();               
        if($treatEndByDeath->count()==0){            
            if ($deaths->count()>0) $deaths->first()->delete();
            $death=NULL;
        }else{
            $death= $deaths->first();  
            if($death==NULL){
                $death= new \App\death();
                $death->parent=$this->id;
                $death->date=$treatEndByDeath->first()->end_date;
                $death->cause=0;
                $death->checkAndUpdate();
            }
        }   
        return $death;
    }        
}
