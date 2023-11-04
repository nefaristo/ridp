<?php
//superdirty utils for this app alone
namespace App\Libs;
use App\RDPModel;
use App\SVLibs\utils as SVutils;
//reminder:
//forms are
class utils{//static lowbrow utilites  
    static function genericButton($params){//ajax buttons
        $id=(isset($params["id"]))?("id='{$params["id"]}'"):"";
        $src=(isset($params["icon"]))?("src='/images/{$params["icon"]}.png'"):"";
        $onClick="onClick='".(isset($params["onClick"])?"'{$params["onClick"]}'":"return false;'");
        return "<input type='image' {$id} {$src} {$onClick} class='icon_button'  />";
    }
    
    static function Button($params){//ajax buttons
        //["type"=>"save|undo|delete","model"=>$model,"section"=>"mainform"|"subform","confirm"=>"D"]
        //NB in the ajax call, data are specified anyway because of multiple forms case: loads only the closest form on many within the container to be refreshed
        $type=(isset($params["type"]))?$params["type"]:"save";
        $class="icon_button"; $display="inline";
        $onClick="";
        switch($type){
        case "undo":
            $onClick="SVL.reset($(this).closest('form'));return false;";
            break;
        default: //save delete add: ajax
            $model=$params["model"];$updatable=$model->permissions();
            if (!(($type=="save" && !$updatable["M"])||($type=="delete" && !$updatable["D"])||($type=="add" && (!$model->permissions()["M"] || $model->id)))){
                $section=(isset($params["section"]))?$params["section"]:"mainform";                        
                $afterDone="function(data){if(typeof MainTree!='undefined'){MainTree.refresh();}}";
                $modelClass= str_replace("app\\","",strtolower(get_class($model)));//LOWER CASE        
                $class.=($type=="save"?" show_changed":""); //save shown only if form is changed
                $op=($type=="delete"?"D":"M"); 
                $onClick="SVL.load({target:$('#{$section}_container'),data:$(this).closest('form').serialize(),url:'/{$op}/".addslashes($modelClass)."',afterDone:$afterDone});return false;";
$onClick="return RDP.checkAndUpdate($(this),'{$op}',$('#{$section}_container'));";
$onClick="return RDP.update({element:$(this),updateType:'M',responseType:'json'});";
                if($type=="delete" && isset($params["confirm"]) && str_contains($params["confirm"],"D"))//confirm on delete if asked
                    $onClick.="if(confirm('".str_replace("'","\'",trans("rdp.delete_confirm"))."')){" . $onClick ."};return false;";
            }
        }  
        if(isset($params["onClick"])) $onClick=$params["onClick"];
        return $onClick? "<input type='image' src='/images/{$type}.png' data-toggle='tooltip' title='".trans("rdp.{$type}")."' class='{$class}' onClick=\"{$onClick}\" />" : "";
    }

    static function ErrorsDiv($errors=[]){
        $result="";
        if (count($errors) > 0){
            $result="<div class='sv_error' style='display:inline-block'>a<ul>";
            foreach ($errors->all() as $error)$result.="<li>{$error}</li>";
            $result.="</ul></div>";
        }
        return $result;
    }
    
    static function validationError($errors){
        $result="";
        return SVutils::viewStuff($errors);
        return $result;
    }
    
    static function link($link,$text){
        if($link!=url()->current()){
            $text="<a href='$link'>$text</a>";
        }else{
            $text="<span class='selected'>$text</span>";
        }
        return $text;
    }
    
    static function LoadingImage(){ 
        return str_replace("'", "\'", "<img src='/images/loading.gif' style='height:60px;weight:60px;float:center'>");
    }
}
?>
