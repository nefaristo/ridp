<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\SVLibs;

use Collective\Html\HtmlFacade as Html;
use Collective\Html\FormFacade as CollForm;
use App\SVLibs\utils;
use \Carbon\Carbon;
/**
wraps Collective/Form adding some feature (select list from DB select...)
 */
class SVL {
    public static function openForm($htmlAttributes=[],$options=[]){
        $htmlAttributes=utils::addArrayStrings($htmlAttributes,
            [
                "data-csrf-token"=>csrf_token(),
                "data-confirm_out"=>(trans('rdp.dirty_form_exit')),
                "data-confirm_delete"=>(trans('rdp.delete_confirm')),
            ]
        );        
        //reminder:$htmlAttributes=utils::addArrayStrings($htmlAttributes,['class'=>"sv_readonly"]);
        return CollForm::open($htmlAttributes); //it echoes token too        
    }
    public static function closeForm(){return CollForm::close();} 
    
    public static function input($type,$name,$value=NULL,$htmlAttributes=[],$options=[]){
        //prepares data - name:         
        if(is_array($value)){$name.="[ ]";}//https://stackoverflow.com/questions/1688599/square-brackets-in-html-form-arrays-just-conventional-or-with-a-meaning
        //options:
        $listSource=$options["list_source"]??"";
        $decimals=$options["decimals"]??NULL; 
        $placeholder=$options["placeholder"]??""; 
        //attributes:
        $htmlAttributes=utils::addArrayStrings ($htmlAttributes, ["data-field"=>$name]);//...leave it.
        $htmlAttributes=utils::addArrayStrings ($htmlAttributes, ["data-initial-value"=>$value?(is_array($value)?json_encode($value):$value):""]);//to detect changes
        $htmlAttributes=utils::addArrayStrings($htmlAttributes,['class'=>config("SVLibs.form.input_class")]);//basic default SVL form class
        if($placeholder)$htmlAttributes=utils::addArrayStrings($htmlAttributes,["style"=>"display:none"]); //real field is hidden to show placeholder first              
        //if (\Auth::user()->privilege>=100) return utils::viewStuff(["type"=>$type,"name"=>$name,"value"=>$value,"htmlAttributes"=>$htmlAttributes,"options"=>$options]);//DEBUG
        //calls underlying method depending on type:        
        switch ($type){ 
        case "select":     
            $result=CollForm::select($name,utils::listOptions($listSource),$value,$htmlAttributes,"");                          
            break;
        case "date":  
            $htmlAttributes=utils::addArrayStrings($htmlAttributes,["format"=>"dd/MM/yyyy"]);//SVSV TODO TEMPORARY WORKAROUND, GO ON WITH NEW INPUT IN MODEL!!!!
            $result=CollForm::date($name,$value,$htmlAttributes);                  
            break;
        case "number":
            if($decimals){$htmlAttributes=utils::addArrayStrings($htmlAttributes,['step'=>pow(10,(-$decimals))]);}
            $result=CollForm::input($type,$name,$value,$htmlAttributes);                 
            break; 
        case "textarea":
            $result=CollForm::textarea($name,$value,$htmlAttributes);                 
            break;
        case "checkbox":
            $result=CollForm::checkbox($name,true,$value,$htmlAttributes);                    
            break;
        case "tree":
            function traverse(&$arr,$name=""){
                $res="";
                if(is_array($arr)){
                    $res.="<ul ".($name?"id='$name'":"").">";
                    foreach($arr as $k=>$v){
                        $res.="<li>$k".traverse($v)."</li>";
                    }
                    $res.="</ul>";
                }else{
                    $res=$arr;
                }
                return $res;
            }  
            $htmlAttributes =utils::addArrayStrings($htmlAttributes,["id"=>$name]);
            return Html::ul($listSource, $htmlAttributes);
            $result= traverse($listSource,$name);
            
            //$result=utils::viewStuff($listSource);
            break;
        default:                
            $result=CollForm::input($type,$name,$value,$htmlAttributes);
        }        
        if($placeholder)$result="<span ".Html::attributes(["class"=>config("SVLibs.form.input_placeholder_class"),"data-field"=>$name]).">{$placeholder}</span>".$result;
        return $result;
    }  
    
    public static function label($name="",$value="",$htmlAttributes=[]){
        return '<label '. ($name?(' for "'.htmlentities($name) . '" '):' ') . Html::attributes($htmlAttributes) . ">{$value}</label>";                               
    }
}
