<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class _s_session extends Model
{
    public $table="_s_session";
    public $timestamps=false;  
    public function log(){
        return $this->hasMany('App\_s_log','session_id')->orderBy("timestamp","ASC");  
    }       
}

  
