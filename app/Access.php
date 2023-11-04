<?php
namespace App;

use App\RDPModel;

class Access extends RDPModel
{
    protected $table='accesses';
    public static $parentClass='Treatment';
    protected $guarded=['id'];
}
