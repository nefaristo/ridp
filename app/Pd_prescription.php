<?php
namespace App;

use App\RDPModel;
use App\Clinical;

class Pd_prescription extends RDPModel
{
    public static $parentClass='Follow_up';
    protected $guarded=['id','loading_volume'];
    public function title(){return $this->parentModel()->title();}
    public function standard2volume(){//from ml/m2 to ml
         $clinical=Clinical::where("parent","=",$this->parent);
        if($clinical->count()){
            $bsa=$clinical->first()->BSA();
            if($bsa){ return round(100*$this->standard_loading*$bsa)/100;}
        }
        return NULL;
    }
    public function volume2standard($volume){//from absolute ml to ml/m2
        $clinical=Clinical::where("parent","=",$this->parent);
        if($clinical->count()){
            $bsa=$clinical->first()->BSA();
            if($bsa) return round(100*$volume/$bsa)/100;
        }
        return NULL;
    }
}
