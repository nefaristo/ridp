<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StandardLaravelExcel
 *
 * extends SeriealiserInterface and implements its methods to have headers...
 * since the original getHeader method doesn't allow arguments (???!!), the class
 * overrides the constructor accepting either an header array or a collection from which header is extracted
 */

namespace App\Serialisers;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Collection;

use Cyberduck\LaravelExcel\Contract\SerialiserInterface;

class LaravelExcelSerialiser implements SerialiserInterface{
    public $header=[];
    public function __construct($header) {//header = array | collection
        if(is_array($header)){
           $this->header=$header;
        }else{//collection        
            $o=[];
            if($header->count()){//keys of first row if collection not empty, nothing otherwise
                foreach($header[0] as $k=>$v){$this->header[]=$k;}
            }
        }
    }
    public function getData($data)
    {
        if ($data instanceof Model) {
            return $data->toArray();
        } elseif (is_array($data)) {
            return $data;
        } else {
            return get_object_vars($data);
        }
    }

    public function getHeaderRow()
    {
        return $this->header;
    }    
}
