<?php
namespace App;

use App\RDPModel;

class Hd_prescription extends RDPModel
{
    public static $parentClass='Follow_up';
    protected $guarded=['id'];
    public function title(){return $this->parentModel()->title();}
}
