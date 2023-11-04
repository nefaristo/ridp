<?php
namespace App;

use App\RDPModel;
use App\_l_therapies;
use Illuminate\Support\Facades\App;
use App\SVLibs\SVL;
use Illuminate\Support\Facades\DB;

class Therapy extends RDPModel
{
    protected $table='therapies';
    public static $parentClass='Follow_up';
    protected $guarded=['id'];
    
    protected $periodList=[
        "en"=>[["d"=>"day","w"=>"week","m"=>"month"],["d"=>"days","w"=>"weeks","m"=>"months"]],
        "it"=>[["d"=>"giorno","w"=>"sett","m"=>"mese"],["d"=>"giorni","w"=>"sett","m"=>"mesi"]],
    ];
    protected $periodValues=["d"=>1,"w"=>7,"m"=>30.44];    
    protected $labels=[
        "num"=>["en"=>["time","times"],"it"=>["volta","volte"]],
        "den"=>["en"=>["every","every"],"it"=>["ogni","ogni"]]
    ];
    
            
    public function check($op="M",$input=NULL) {
        parent::check($op,$input);
        if($this->_l_therapies("calc_uom") && !$this->calc_dose) 
            //$this->errors->add("dose",trans("rdp.errors.calc_dose_missing"));REMOVED BY REQUEST 20/6/19
        return $this->errors;
    }    

    public function label($name, $value = NULL, $htmlAttributes = array()) {
        $result="";
        switch($name){
            case "administration_via":
                if ($this->type){ 
                    $ref=_l_therapies::where("id","=",$this->type)->get();
                    if(!($ref->count()>0 && $ref->first()->administration_via_options))return"";                        
                }
                break;
            case "specification":
                $value=$this->_l_therapies("specification");
                if(!$value) return"";
                break;
            case "uom": case "calc_uom":
                if($this->_l_therapies("uom")){//only if dose is expected
                    $value=$this->_l_therapies($name);
                    if(!$value)return"";
                }
                break;
            case "num":case"den":
                $value=$this->labels[$name][\App::getLocale()][$this->{$name}>1?1:0];
                break;
        }
        return parent::label($name, $value, $htmlAttributes);;
    }
    

    public function input($name,$htmlAttributes=[],$options=[]) {
        switch($name){
            case "type"://list with non already existing therapies [+therapy of this record]
                if (!$this->parent) return"-";
                $fieldName="name_".App::getLocale();
                $options["list_source"]="SELECT _l_t.id AS id, $fieldName FROM "
                    . "(SELECT id, $fieldName, IF($fieldName LIKE '%altro%',1,0) as ord FROM _l_therapies ORDER BY ord) _l_t "
                    . "LEFT JOIN "
                    . "(SELECT * FROM therapies WHERE therapies.parent=".$this->parent.") fu_therapies "
                    . "ON _l_t.id=fu_therapies .type "
                    . "WHERE fu_therapies.parent IS NULL "
                    .(($this->type)?"OR _l_t.id = ".$this->type:"");                
                break;
            case "administration_via": //no adm.via options from this drug => no input                                                 
                $options["list_source"]=$this->_l_therapies("administration_via_options");//no adm.via options => no input
                if(!$options["list_source"]) return "";
                break;                
            case "specification": //no spec. label from this drug => no input               
                if(!$this->_l_therapies("specification"))return "";
                break;
            case "dose": case "num":case "den": case "calc_dose":
                if(!$this->_l_therapies("uom"))return "";
                break;
            case "period": //only if dose
                if($this->_l_therapies("uom")){
                    $result= json_encode($this->periodList[\App::getLocale()][$this->den>1?1:0]);
                    $options["list_source"]=$this->periodList[\App::getLocale()][$this->den>1?1:0];
                }else{
                    return "";
                }
                break; 
        }
        return parent::input($name, $htmlAttributes, $options);
    }
    
    public function group($groupName,$changedField=NULL,$newLine=false){//custom dirty bunch of html elements. changed is the optional name of the field that's been changed
        $result="";
        $phYN=!$newLine;$brYN=$newLine;//placeholder and br? (kept here in case of changes)        
        switch($groupName){
        case "type_spec_via_group":
            //type:
            $result=$this->input("type",[],($phYN?["placeholder"=>"?"]:[])). ($brYN?"<br>":" ");
            //specification:
            $spec=$this->_l_therapies("specification");
            if($spec)$result.=" (".$this->input("specification",[],($phYN?["placeholder"=>$spec]:[])) . ")" .($brYN?"<br>":" ");
            //administration via:
            $result.=$this->label("administration_via","via") . " " ;
            $result.=$this->input("administration_via",[],($phYN?["placeholder"=>"?"]:[]));
            break;
        case "dose_group": 
            if($this->_l_therapies("uom")){
                $to=($changedField=="calc_dose"?"dose":"calc_dose");
                //actual calculation & attributed result:
                if($changedField)$this->{$to}=$this->calculate_dose($changedField);
                $short=["style"=>"width:3em;"];
                $result.=
                    $this->input("dose",$short,($phYN?["placeholder"=>"?"]:[])). " ". $this->label("uom"). " ".
                    $this->input("num",$short,($phYN?["placeholder"=>"?"]:[])). " ". $this->label("num"). " ".
                    $this->label("den"). " ". $this->input("den",$short,($phYN?["placeholder"=>"?"]:[])). " ".
                    $this->input("period",[],($phYN?["placeholder"=>"?"]:[])). 
                    ($brYN?"<br>(":" ("). $this->input("calc_dose",$short,($phYN?["placeholder"=>"?"]:[])). " ".$this->label("calc_uom"). " )";
                        "";
            }
            break;
        }
        return $result;
    }
            
    public function calculate_dose($from="dose"){//raw or calculated dose calculation 
        $result=NULL;
        //possibly changes other empty attributes to perform the calculation:
        if(!$this->num || $this->num<1)$this->num=1;
        if(!$this->den || $this->den<1)$this->den=1;
        if(!$this->period)$this->period="d";
        if($this->{$from}){
            $calculationType=$this->_l_therapies("calc_uom","-");//retrieves formula "label" from drugs table                                    
            switch($calculationType){
            case "unit/kg/w":              
                $clinical=\App\Clinical::where("parent","=",$this->parent)->get();
                if($clinical->count()>0){
                    $weight=$clinical->first()->weight;
                    if($weight && $this->num && $this->den && $this->period){
                        if($from=="calc_dose"){//calc_dose=7*dose*num/(den*period*weight)
                            $result=$this->calc_dose*($this->den*$this->periodValues[$this->period]*$weight)/7*$this->num;
                        }else{//inverse:dose=calc_dose*(den*period*weight)/(7*num)
                            $result=7*$this->dose*$this->num/($this->den*$this->periodValues[$this->period]*$weight);
                        }
                    }
                }
            break;
            case "unit/d":
                if($this->num && $this->den & $this->period){
                    if($from=="calc_dose"){//calc_dose=dose*num/(den*period)
                        $result=$this->calc_dose*$this->den*$this->periodValues[$this->period]/$this->num;
                    }else{//inverse:dose=calc_dose*den*period/num                        
                        $result=$this->dose*$this->num/($this->den*$this->periodValues[$this->period]);
                    }
                }
                break;
            default:
                $result=$this->{$from};
            };
        }
        return is_numeric($result)?(round(10*$result)/10):NULL;
    }
    
    public function _l_therapies($feature=NULL,$lang=null){
        //uom|specification|administration_via_options|name
        //for unit|label for specification if needed|list of adm via options if needed
        if ($lang==NULL)$lang=\App::getLocale();
        $result=NULL;
        if($this->type){
            $ref=_l_therapies::where("id","=",$this->type)->get();
            if($ref->count()>0){
                switch($feature){
                    case "name":
                        $feature.="_$lang"; return $raw=$ref->first()->{$feature};
                        break;
                default:
                    $raw=$ref->first()->{$feature};
                    if($raw){$result=json_decode((string)$raw,true)[$lang];}
                }                
            }
        }
        return $result;
    }
}
