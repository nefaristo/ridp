<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class _s_log extends Model
{
    public $table="_s_log";
    public $guarded=[];//mass assignment for all
    public $timestamps=false;
    public function recordClass(){
        return '\App\\'.ucfirst($this->model);
    }
    public function recordObj(){
        if($this->model && $this->id){            
            $class=$this->recordClass();
            $model=$class::find($this->record);
            if($model)return $model;
        }
        return NULL;
    }
}

  
