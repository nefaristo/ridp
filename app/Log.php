<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $table="_s_log";
    public $timestamps=false;  
    
    public function logUser(){
        return $this->belongsTo('App\User','user');  
    }
}

  
