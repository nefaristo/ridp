<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;
    public $timestamps=false;  //SVSV check if it's working
    protected $appends = ['timezone','dateformat'];
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
    
    public function userCenter(){
        $result= $this->belongsTo('App\Center','center');
        return ($result!=NULL)?$result->first():NULL;
    }      
    
    public function UFPrivilege(){
        switch($this->privilege){
            case 0:$result="-";break;
            case 1:$result="user";break;
            case 10:$result="superuser";break;
            case 100:$result="admin";break;
            default:$result="?";
        }
        return $result;
    }
    public function passwordResetNotification($token){
        $data=trans("rdp.PasswordResetMail");
        date_default_timezone_set("Europe/Rome");
        $expDateTime=Carbon::now();
        $data["intro"]=
            $data["intro"]=str_replace("{username}",$this->username,$data["intro"]);
            $data["outro"]=str_replace("{expDateTime}",$expDateTime->addMinutes(config("auth.passwords.users.expire"))->format("d/m H:i"),$data["outro"]);
        return new \App\Notifications\ResetLinkMail($token,$data);
    }
    public function sendPasswordResetNotification($token){
        $this->notify($this->passwordResetNotification($token));
    }
    
    //TODO:
    public function getTimezoneAttribute(){return "Europe/Rome";}
    public function getDateformatAttribute(){return "d/m/Y";}
}
