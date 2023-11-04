<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Center;
use App;

class _s_datacheck_log extends Model
{
    protected $table="_s_datacheck_log";
    protected $appends=["center_title"];
    public $timestamps=false;
    
    public function getCenterTitleAttribute(){
        if($this->center){
            return Center::find($this->center)->title();
        }else{
            return null;
        }
    }
    
}
