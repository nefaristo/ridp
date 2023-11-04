<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User_update extends RDPModel
{
    use Notifiable;
    protected $table='users';   
    public static $parentClass='Center';//OVERRIDE - only class name of parent (ie="Patient"); 
    
    public function title(){return $this->username;}
    
    public function permissions($user=NULL){//modify only the inferior privileges + no deletions
        //A,D false; M<=> your user || your privilege at least superuser, user's privilege lower than yours
        if ($user==NULL)$user=Auth::user();
        $result["M"]=($this->id==$user->id || ($user->privilege>$this->privilege && $user->privilege>=10));
        $result["D"]=false;
        $result["A"]=$result["M"];
        return $result;
    }
               
    public $timestamps=false; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded=['id'];
    /*protected $fillable = [
        'username', 'email', 'password',
    ];*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function parentModel(){//SVSV TODO override since column name is "center" and noto "parent"
        $result=($this::$parentClass==NULL)?NULL:$this->belongsTo ('\App\\'.$this::$parentClass,'center');
        return ($result!=NULL)?$result->first():NULL;
    }
    
    public function user(){
        return \App\User::find($this->id);
    }
    
    public function setActiveAttribute($value)
    {
        $this->attributes['active'] = $value?1:0;
    }
}
