<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\SVLibs;
use Illuminate\Support\MessageBag;

class Messages implements \JsonSerializable{
    const JOLLY = "_all_";
    protected $core;
    
    public function __construct($model=NULL){
        $this->core=[
            "info"=>["errors"=>0,"warnings"=>0,"model"=>$model],
            "errors"=>new MessageBag(),"warnings"=>new MessageBag(),
        ];
    }
    
    public function __set($name,$value){
        switch($name){
        case "errors":case"warnings":
            $this->core[$name]=$value;
            break;
        }
    }
    public function __get($name){
        switch($name){
        case "info": case "errors":case "warnings":
            return $this->core[$name];
            break;
        default:
            return null;
        }          
    }
    public function jsonSerialize() {
        return $this->core;
    }
    
    public function add($message,$key=JOLLY,$type="errors"){
        switch($message){
        case(is_string($message)):
            $this->core[$type]->add($key,$message);
            $this->core["info"][$type]+=1;
            break;
        case (get_class($message)=="\App\SVLibs\Messages"):
            $this->core["info"]["errors"]+=$message->info["errors"];
            $this->core["info"]["warnings"]+=$message->info["warnings"];
            $this->core["errors"]->merge($message->errors);
            $this->core["warnings"]->merge($message->warnings);                
            break;
        }

        //PROTECTED
        
    } 
}
