<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App;

class _s_query extends Model
{
    protected $table="_s_queries";
    public $timestamps=false;
    
    public function sql($lang=NULL){
        if(!$lang)$lang=App::getLocale();
        return str_replace("_*","_{$lang}",
            $this->sql_text?$this->sql_text:("SELECT * FROM ".$this->name) //explicit sql text or select from [name]
        ); //as of now this replace is not used
    }
    
    public function name($lang=NULL){
        $fieldName="name_".($lang?$lang:App::getLocale());
        return ($this->$fieldName?$this->$fieldName:$this->name);
    }
}
