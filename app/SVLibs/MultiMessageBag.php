<?php
namespace App\SVLibs;
use App\SVLibs\utils;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelFormInfo
 *
 * @author utenzio
 */
const MMB_DEFAULT_=["errors","warnings","messages"];
class MultiMessageBag {
    protected $data=[];
    public function __construct($bags=NULL){//either [name=>messageBag,] or [names list,]
        if(!$bags) $bags=MMB_DEFAULT_;
        if(utils::isAssoc($bags)){//[name=>messageBag,]
            $this->data=$bags;
        }else{//[names list,]
            $this->clear($bags);
        }
    }
    public function clear($what=NULL){//default=all
        if(!$what) $what=$this->data;//all
        foreach($what as $k){
            $this->data[$k]=new \Illuminate\Support\MessageBag;
        }
    }
    public function __get($property){
        switch($property){
            case "errors":case "warnings":case "messages":
                return $this->data[$property];
                break;
        }
    }
    public function __set($property,$value){
        switch($property){
            case "errors":case "warnings":case "messages":
                $this->data[$property]=$value;
                return $this->data[$property];
                break;
        }        
    }
    public function merge($with=NULL){
        if($with){
            if(get_class($with)!=__CLASS__."\\SVLibs\\ModelFormInfo"){
                $with=new \App\SVLibs\ModelFormInfo($with);
                $this->data["errors"]->merge($with->errors);
                $this->data["warnings"]->merge($with->warnings);
                $this->data["messages"]->merge($with->messages);
                return $this; 
            }
        }    
    }
}
