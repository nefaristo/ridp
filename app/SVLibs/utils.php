<?php


namespace App\SVLibs;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;

class utils{//static utilites
    
    static $lang="en";
    private static $dateFormat_=[
        'it'=>['standard'=>'d/m/Y','short'=>'d/m/y'],
        'en'=>['standard'=>'Y-m-d','short'=>'y-m-d'],
    ];
    
    static function init(){self::$lang=App::getLocale();}

    /******************** DATE AND LOCALE RELATED ***************************/
    
    public static function listOptions($source){//list suitable for Form::select (therefore a [category]=> [key]=>"shown value")
        //source is either a json array: {category:{key:shown value,..}..}
        //or a sql "SELECT key, shown value, category" 
        //category and value optional in both cases
        if(is_array($source))return $source;//more flexible 
        $result=[];
        if(strlen($source)>6 && strtoupper(substr($source,0,6))=="SELECT"){//SELECT STATEMENT:
            $raw=json_decode(json_encode((array) DB::select($source)), true); 
            if($raw){
                $cols=count(array_values($raw[0]));//1:key, 2:also value, 3: also category
                foreach($raw as $record){
                    $record=array_values($record);//actual record is the indexed array content
                    switch($cols){
                        case 1://1 col: from [key1,key2] to [key1=>key1,key2=>key2]
                            $result[$record[0]]=$record[0];
                            break;
                        case 2://2 cols: [key1=>val1,key2=>val2], left as it is
                            $result[$record[0]]=$record[1];
                            break;
                        default://3+cols: [key1,val1,cat1] from A=>[1=>a,2=>b] to [1=>1,2=>2]
                            $result[$record[2]][$record[0]]=$record[1];
                    };
                }
            }
        }else{//json array string:
            $raw=json_decode($source,true); 
            if(is_array($raw)){
                if(!utils::isAssoc($raw)){//indexed array 2 assoc: from [key] to [key=>key]
                    foreach($raw as $record) $result[$record]=$record;
                }else{
                    $result=$raw;//...associative as it is otherwise.
                }
            }else{
                $result=$raw;//not recognized=>returns raw string
            }
        }       
        return $result;
    }   
    
    public static function sqlCheck($sql){
        //fastest way available to see if the query works/how many records
        try{
            return utils::viewStuff(DB::select("SELECT COUNT(*) as N FROM (" . $sql . ") AS dummy")[0]->N);
        }catch(\Exception $e){  
            return -1; 
        }   
    } 

    /******************** ENCODE/DECODE RELATED ***************************/
    
    public static function url2array($input){//string2array applied with http with possible layer of json content
        $res=self::string2array($input,"http");
        foreach($res as $k=>&$v){
            if(is_array($v)){//http can itself have an array ! I.e. var[]=val&al=etc
                foreach($v as $k2=>&$v2){//no recursion needed, it's a 2D array at most
                    $temp=self::string2array($v2,"json",true);
                    if($temp!==NULL){$v2=$temp;}
                }
            }else{ 
                $temp=self::string2array($v,"json",true);
                if($temp!==NULL){$v=$temp;}
            }            
        }
        return $res;
    }
    
    public static function string2array($input,$format=NULL,$nullIfFail=false){
        /*decodes string into array/wrapper for various decodes: 
         * if $type = json|http|xml is passed, the input is decoded into that;
         * if it's not, the input is decoded into json, http, xml in this order; the detected type is passed back.
         * nullIfFail => returns NULL if the input doesn't fit the type/types; otherwise[]
         */
        static $guessFormats=["json","xml","http"];
        //if (!is_string($input)){return [];}               
        switch($format){
        case "json":
            $res=json_decode($input,true);//NULL if fails
            break;
        case "xml":
            $res=simplexml_load_string ($input,'SimpleXMLElement',LIBXML_NOERROR);//simpleXMLElement
            if($res===FALSE){//strict comparison needed!
                $res=NULL;
            }else{
                $res=json_decode(json_encode((array)$res,1));//to array
            }                
            break;            
        case "http":
            parse_str ($input,$res);
            if(!str_contains($input, "=")) $res=NULL;//a string w\o "=" is NOT accepted as http here.
            break;
        case NULL://tries them all:                
            foreach($guessFormats as $guessFormat){   
                $res=self::string2array($input,$guessFormat);//foreach element is passed by value so it's ok
                if($res){break;}
            }
            break;
        default://no accepted type selected
            $res=NULL;
        }
        return ($res===NULL && !$nullIfFail)?[]:$res;
    }           
   
    /******************** DB RELATED ***************************/
    static function DBSelect($sql,$parameters=[],$loose=false){
        //PDO &/| Query builder apparently can't user parametrs: (1) more than once (2) as column names.
        //the "loose" option (to be called in an appropriately safe environment) does a simple string replacement 
        if($loose){
            foreach($parameters as $k=>$v){
                $sql=str_replace(":".$k, $v, $sql);
            }
            return DB::select(DB::raw($sql));
        }else{
            return DB::select(DB::raw($sql),$parameters);
        }
    }
    static function exceptionInfo(\Exception $e){//array of error info (see below) for DB error, NULL otherwise
        $result=[
            'sql_state'=>'','code'=>'','sql'=>'','original_message'=>'',
            'message'=>'non-sql error (class: '.get_class($e).')'];
        if ($e instanceof \Illuminate\Database\QueryException){//SQL 
            $result["message"]="generic sql error";
            $sql = $e->getSql();$bindings = $e->getBindings();
            // Process the query's SQL and parameters and create the exact query
            foreach ($bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else {
                    if (is_string($binding)) {
                        $bindings[$i] = "'$binding'";
                    }
                }
            }
            $query = str_replace(array('%', '?'), array('%%', '%s'), $sql);
            $query = vsprintf($query, $bindings);
            $errorInfo = $e->errorInfo;
            $result = [
                'sql_state'  => $errorInfo[0],
                'code' => $errorInfo[1],
                'sql'        => $query,
                'original_message'    => isset($errorInfo[2]) ? $errorInfo[2] : '',
                'message'=>$message="SQL ERROR - query: " .$query." | message: ". (isset($errorInfo[2]) ? $errorInfo[2] : '') . " | state.err:". $errorInfo[0].".".$errorInfo[1],
            ];                
            $state_err=$errorInfo[0].".".$errorInfo[1];
            $errsArray=config("SVLibs.sql_errors.".self::$lang);
            //if a user friendly is found in the table, message is 
            if(array_key_exists($state_err,$errsArray))
                $result["message"]=$errsArray[$state_err];                
        }
        return $result;
    }
    
    public static function db2html($options=[]){//only table for now
    
        //laravel query or select+where+orderby strings:
        $query=$options["query"]??""; $parameters=$options["parameters"]??""; 
        $select=$options["select"]??"";$where=$options["where"]??"";$orderBy=$options["orderBy"]??"";
        //css:
        $class=key_exists("class", $options)?$options["class"]:"";
        //other options:
        $showError=key_exists("showError", $options);
        $columnLink=key_exists("columnLink", $options)?$options["columnLink"]:"";//format "...{column}..."
        $noEncoding=key_exists("noEncoding", $options);
        $result="";$error=NULL; 
        if($query==""){
            if($where) $select.= " WHERE ".$where;//TODO SQL injection prone! but select returns an array so you can't call where()/orderby()
            if($orderBy) $select.= " ORDER BY ".((strpos($orderBy," ")>0)?("'".$orderBy."'"):$orderBy);//TODO SQL injection prone!
            $query=DB::select(DB::raw($select),$parameters);
        }         
        try{$db =json_decode(json_encode((array) $query, true));}      
        catch(\Exception $e){$error=self::exceptionInfo($e);}
        
        if($error && $showError){; 
            $result="<tr><td>".$error["message"]."</td></tr>";
        }else{
            if(is_array($db) && count($db)>0){                
                //header:
                $trContent="";
                foreach($db[0] as $k=>$v)
                    $trContent.=$columnLink?"<th><a href='".str_replace("{column}",$k,$columnLink)."'>{$k}</a></th>":"<th>{$k}</th>";
                $result.="<tr>{$trContent}</tr>";
                //other:    
                foreach($db as $row){
                    $trContent="";
                    foreach($row as $val)
                        $trContent.="<td>".($noEncoding?$val:htmlentities($val))."</td>";
                    $result.="<tr>{$trContent}</tr>";
                }                
            }
            $result="<table class='{$class}'>{$result}</table>";
        }
        return $result;
    }  
    
    /******************** LOCALE RELATED ***************************/
    
    public static function langDecode($source="",$lang="NULL"){        
        //{"en":"english text","ot":"other"}' => "english text" or "other"
        if (!$lang)$lang=self::$lang;
        $raw=json_decode($source,true); 
        if(is_array($raw) && key_exists($lang,$raw)){ 
            return $raw[$lang];
        }else{
            return $source;
        }
    }
    
    public static function dateFormat($lang=NULL,$var="standard"){
        if($lang==NULL) $lang=self::$lang;  
        return self::$dateFormat_[$lang][$var];
    } 
    
    public static function date2string($date,$lang=NULL){
        //input: string|date|null
        if(!$lang)$lang=self::$lang;
        if(is_null($date))return "";
        if (is_string($date)) $date=($date<>"")?strtotime($date):"";    
        if (is_object ($date)){
            if(get_class($date)=="Carbon\Carbon"){
                return Carbon::parse($date)->format(self::dateFormat($lang));
            }else{
                return $date;//???
            }
        }else{     
            return date("d/m/Y",$date);
            //return (is_numeric($date))?("number".$date):"other";
        }        
    }
    
    /******************** BASIC UTILITES ***************************/
    
    static function viewStuff($obj,$expandedLev=1,$level=0,$addType=false){ 
        $result="";$color='hsl('.($level*36). ',100%,50%)';
        $type= gettype($obj);if(is_a($obj,"Carbon\Carbon"))$type="carbon";//ADDED 260218
        switch ($type){
            case "resource": $type=get_resource_type($obj); break;
        }                
        switch ($type){
            case "array":
                if(count($obj)>0){
                    foreach ($obj as $key => $value){
                        $inLine=!is_array($value);
                        $compress=!$inLine &&  $level>=$expandedLev;//compressed if needed (array with too many children) and not explicitly excluded                                    
                        $result.= $inLine?"":"<details".($compress?"":" open")."><summary>";
                        $result.= '<b>'.$key.'</b> =&gt';
                        $result.= $inLine?"":"</summary>";
                        $result.= self::viewStuff($value,$expandedLev,$level+1,$addType);
                        $result.= $inLine?"<br/>":"</details>";
                    }                    
                    $result='<div style="margin: 5px 5px 5px 5px; border: 2px solid; border-color: '.$color.';">'.$result.'</div>';
                }else{
                    $result="";
                }
                break;
            case "NULL":$result="";break;
            case "string":$result=$obj;break;
            case "integer":case "double":$result=(string)$obj;break;  
            case "boolean":$result=$obj?1:0;break;
            case "object":
                $type=get_class($obj); 
                switch($type){
                    case "SimpleXMLElement":
                        $compress=$level>=$expandedLev && (count($obj->children())>1);                        
                        $result.= $compress?"<details><summary>":"";                    
                        $result.='<b>&lt;'.$obj->getName().'</b>';
                        $attsInLine=(count($obj->attributes())<=3);//not too many atts => everything in line
                        $result.=$attsInLine?'':'<br/>';
                        foreach ($obj->attributes() as $key => $value){
                            $result.= $attsInLine?'&nbsp;':'&nbsp;&nbsp;&nbsp;&nbsp;';
                            $result.= $key . '= "' . htmlentities($value) . '"';
                            $result.= $attsInLine?'':'<br/>';
                        }
                        $result.='<b>&gt;</b>';
                        $result.=(string)$obj!=""?'<br>'.(string)$obj:"";//actual content
                        $result.= $compress?"</summary>":"";
                        foreach ($obj->children() as $child){
                            $result.= self::viewStuff($child,$expandedLev,$level+1,$addType);
                        }  
                        $result.= $compress?"</details>":"";
                        $result='<div style="margin: 5px 5px 5px 5px; border: 2px solid; border-color: '.$color.';">'.$result.'</div>';
                        break; 
                    default://default object
                        $obj=(array)$obj; 
                        if($level<20){
                             $result=self::viewStuff($obj,$expandedLev,$level+1,$addType);
                        }else{
                            $result="overflow - check the actual type";
                        }
                }                
                break;
            case "carbon":
                $result=$obj->format('m/d/Y');
                break;
            default://unknown object
                $result="unknown";var_dump($obj);
        } 
        return $result . ($addType? " (".$type. ")":""); 
    }
    
    static function isAssoc($arr){
        //returns true if is associative, false if sequential. arrays are always associative for php, so sequential means array(0=>x,1=>y)    
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
   
    public static function addArrayStrings($baseArray=array(),$addArray=array(),$sep=" "){
        //f([key=>"aa"],[key=>"bb"])=[key=>"aa" .$sep. "bb"] 
        //mainly to add classes(sep=" ") or styles(sep=";") in a html attributes array
        if(sizeof($addArray)>0){
            foreach($addArray as $key=>$value){
                $oldValueSep=(key_exists($key,$baseArray)?($baseArray[$key].$sep):"");
                $baseArray[$key]=$oldValueSep.$value;
            }
        }
        return $baseArray;
    } 
    
    static function carbonDate($dateText){//NULL for NULL|"", Carbon otherwise;
        if($dateText==NULL){return NULL;}else{return \Carbon\Carbon::parse($dateText);}
    }   
    
    static function trimText($input, $length, $ellipses = true, $strip_html = true) {
        //http://www.ebrueggeman.com/blog/abbreviate-text-without-cutting-words-in-half
        //strip tags, if desired
        if ($strip_html)$input = strip_tags($input);
        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length)return $input;
          //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);  
        //add ellipses (...)
        if ($ellipses) $trimmed_text .= '...';  
        return $trimmed_text;
    }        
    
    static function overlap($range1,$range2,$strict=false){
        //true if ranges overlap (or are adjadcent for strict=false). Accepts numbers, carbon dates, string dates, strings.
        //NULL=infinite, i.e. [NULL,0] are negative, [date(),NULL] is the future etc 
        
        //PRE 1: from single value to range + array 2x2 useful for loops
        if(!is_array($range1))$range1=[$range1,$range1]; if(!is_array($range2))$range2=[$range2,$range2]; 
        $ranges[0]=$range1;$ranges[1]=$range2;
        //PRE 2:determines data type from the first non null element
        foreach($ranges as $range) foreach($range as $el)
            if($el!==NULL && $el!==""){$whatever=$el;break;}//the first nonnull goes        
        if($whatever==NULL) return true; //all null case
        $isDate=strtotime($whatever);if($isDate)$whatever=utils::carbonDate($whatever);//useful for min and max
        //PRE 3: date conversion + NULL-wise min and max determination
        $min=$whatever;$max=$whatever;//faster (no NULL min&max to take into consideration)    
        for($i=0;$i<2;$i++)for($j=0;$j<2;$j++){//loop upon the 4 terms
            if($ranges[$i][$j]!==NULL && $ranges[$i][$j]!==""){
                if($isDate)$ranges[$i][$j]=utils::carbonDate ($ranges[$i][$j]);//conversion to carbon (unconsequential if it's already carbon)
                if($min>$ranges[$i][$j])$min=$ranges[$i][$j];//determines min
                if($max<$ranges[$i][$j])$max=$ranges[$i][$j];
            }
        }
        //PRE 4: determine -+infinite
        if($isDate){    
            $minusInf=new \Carbon\Carbon($min);$minusInf->subDays(1);
            $plusInf=new \Carbon\Carbon($max);$plusInf->addDays(1);
        }else{
            $minusInf=$min-1;$plusInf=$max+1;
        }
        //PRE 5: [NULL,NULL]=>[-inf,+inf](less then min/more max)
        for($i=0;$i<2;$i++){
            if($ranges[$i][0]===NULL || $ranges[$i][0]==="")$ranges[$i][0]=$minusInf;
            if($ranges[$i][1]===NULL || $ranges[$i][1]==="")$ranges[$i][1]=$plusInf;
        }
        //PRE 6: ordering
        sort($ranges[0]);sort($ranges[1]);
        if ($strict){
            return $ranges[0][0] < $ranges[1][1] && $ranges[1][0] < $ranges[0][1];//A1<B2 ^ B1<A2
        }else{
            return $ranges[0][0] <= $ranges[1][1] && $ranges[1][0] <= $ranges[0][1];//A1<B2 ^ B1<A2
        }        
    }

/*
    static function associativeArray($array,$subEl,$delSubEl=true){
        //returns an associative array with the element selected in path used as key
        //f ( [["id"=>"x"],["id"=>"y"]] , '[0]')=[
        //f($
        //function($a ,'[0]')=array($a,'["id"]')=array("x"=>,array("id"=>"x")), etc )
        $result=[];
        foreach($array as $el){
            if(key_exists($subEl,$el)){
                $result[]=$el;
                //$result[$el[$subEl]]=$el;
                //if($delSubEl) unset($el[$subEl]);
            }
        }
        return $result;
    }
    
     * 
     */    
/*
    static function formatJavaScript($string, $doubleQuotesContext = true, $addQuotes = false) {                
        $string = json_encode($string);// Encode as standard JSON, double quotes
        $string = mb_substr($string, 1, -1);// Remove " from start and end"
        if ($doubleQuotesContext === false) {// If using single quotes, reaplce " with ' and escape
            $string = str_replace('\"', '"', $string);// Remove \ from "
            $string = str_replace("'", "\'", $string);// Escape single quotes
        }
        if ($addQuotes === true) {
            if ($doubleQuotesContext === true) {
                $string = '"' . $string . '"';
            } else {
                $string = "'" . $string . "'";
            }    
        }
        return $string;      
    }
*/
}
utils::init();
?>
