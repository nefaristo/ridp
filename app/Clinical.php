<?php
namespace App;

use App\RDPModel;

class Clinical extends RDPModel
{
    public static $parentClass='Follow_up';
    protected $guarded=['id'];
    public function check($op = "M", $input = NULL) {
        parent::check($op, $input);
    }
    public function title(){return $this->parentModel()->title();}
    public function BSA($attributes=NULL){//Haycock
        if($this->weight && $this->height){
            return round(0.024265 * pow($this->height,0.3964) * pow($this->weight,0.5378) * 100)/100;
        }else{ 
            return NULL; 
        }
    }
}
