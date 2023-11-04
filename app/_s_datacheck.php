<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\_s_datacheck_log;
use App\Center;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App;

//NB MOST METHODS USED BY MIDDLEWARE AND/OR SERVICES through refreshlogs=>refreshlog
class _s_datacheck extends Model
{
    protected $table="_s_datacheck";
    protected $appends=['next_update'];
    public $timestamps=false;

    public function getNextUpdateAttribute(){
        return $this->last_update?Carbon::parse($this->last_update)->addHours(config("rdp.misc.update_datacheck_after")):Carbon::now();       
    }
    public function scopeToBeUpdated($query){
        $result=$query->where(DB::raw("DATE_ADD(last_update, INTERVAL update_every HOUR)"),"<",DB::raw("NOW()"))->orWhereNull(DB::raw("last_update"));
        return $result;
    }
    
    public function checkResults(){
        return DB::select(DB::raw($this->query));
    }
    public function centersLog($centerFilter=NULL){//centers involved in this datacheck with their log
        $dcQuery="SELECT center, COUNT(*) AS logCount FROM _s_datacheck_log "
            ." WHERE datacheck=".$this->id.($centerFilter?" AND center={$centerFilter}":"")
            ." GROUP BY center HAVING logCount>0";
        $result=[];
        foreach (DB::select(DB::raw($dcQuery)) as $rec){
            $result[$rec->center]=["center"=>Center::find($rec->center),"log"=>$this->log($rec->center)]; 
        }; 
        return $result;
    }
    
    public function log($center=NULL){
        $result=$this->hasMany('App\_s_datacheck_log', 'datacheck', 'id');
        if($center) $result=$result->where("center","=",$center);
        return $result->get();
    }
    
    
    public function refreshLog($force=false){
//return "";        
        $debug="";
        if ($force || $this->next_update<Carbon::now()){
            //delete old log:
            _s_datacheck_log::where([["datacheck","=",$this->id]])->delete();
            //write new log records:
            foreach($this->checkResults() as $result ){
                $logRec=new _s_datacheck_log;
                $logRec->datacheck=$this->id;
                $logRec->center=$result->center;
                $logRec->text_it=$result->text_it;
                if(property_exists($result, 'patient')){
                    $logRec->text_it="<b>Paziente <a href='".url("/edit/patient/".$result->patient)."' target='blank'>".htmlentities($result->patient_code)."</a></b>:<br>".$logRec->text_it;
                }                
                if(key_exists("note_it", $result)){$logRec->note_it=$result->note_it;}                
                $logRec->save();
                $debug.= htmlentities(json_encode((array)$logRec));
            }
            //refresh datacheck update time:
            $this->last_update=Carbon::now();
            $this->save();
        }
        return $debug;
    }
    
    //STATIC FUNCTIONS ON ALL DATACHECKS

    public static function logs($args=[]){
        $user=\Auth::user();
        $refresh=(key_exists("refresh", $args));
        $datacheck=(key_exists("datacheck", $args))?args["datacheck"]:NULL;
        $center=(key_exists("center", $args))?args["center"]:$user->userCenter();
        $privilege=(key_exists("privilege", $args))?args["privilege"]:$user->privilege;
        
        $log=_s_datacheck_log::whereNotNull("id"); //all() already gives a collection
        if($datacheck) $log=$log->where("datacheck","=",$datacheck);
        if($center) $log=$log->where("center","=",$center);
        //$log->orderBy("datacheck")->orderBy("center");
        $dcId=NULL;$dc=NULL;
        $result=[];
        foreach($log->get() as $logRec){
            if($dcId!=$logRec->datacheck){$dcId=$logRec->datacheck;$dc=_s_datacheck::find($dcId);}
            $result[]=[
                "dc_name_it"=>$dc->name_it,"dc_last_update"=>Carbon::parse($dc->last_update)->format('d/m/Y H:i:s'),
                "text_it"=>$logRec->text_it,"note_it"=>$logRec->note_it
            ];
        }
        return $result;
    }
    
    public static function refreshLogs($force=false){//NB USED BY MIDDLEWARE AND/OR SERVICES
        $toUpdate=$force?_s_datacheck::all():_s_datacheck::toBeUpdated()->get();
        $debug=$toUpdate->count() . " checks to be updated.<br>";
        foreach($toUpdate as $dc){
            $debug.="<hr>".$dc->name_it.":".$dc->next_update."<hr>";
            $debug.= $dc->refreshLog($force); 
        }
        return $debug;
    }
}
