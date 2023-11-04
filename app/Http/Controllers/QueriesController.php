<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\SVLibs\utils;
use \Illuminate\Support\Facades\App;
use App\SVLibs\SVL as SVL;

//use Cyberduck\LaravelExcel\ExporterFacade as Exporter;
//use App\Serialisers\LaravelExcelSerialiser;
use App\Exports\QueryExport;
use Lava;

//use Excel;
//use PHPExcel_Shared_Date;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class QueriesController extends Controller{ 
    
    public function __construct(){$this->middleware('log');}        
    
    public function index($id=null){
        $user=\Auth::user();$center=$user?$user->userCenter():NULL;
        $arrRow=[];
        function traverse($catArr){
            //check your privilege:            
            if(key_exists("privileges", $catArr)){
                if(!in_array(\Auth::user()->privilege, $catArr["privileges"]))return [];
            }
            //root array doesn't have "list"=>[...]:
            if(!key_exists("list",$catArr)) return traverse(["list"=>$catArr]);
            //actual list:    
            $subList=[];
            foreach($catArr["list"] as $k=>$v){//root element has no 
                if(is_array($v)){//array=other category
                    $t=traverse($v);
                    if($t)$subList[$k]=$t;
                }else{//leaf=query
                    $subList[$v]=config("queries.details.$v.name_it");
                }
            }
            return $subList;
        }
        $ddArray=traverse(config("queries.categories"));
    if(\Auth::user()->privilege>=1100){//tinkering  with a "tree" pseudoinput for SVL
        $ddArray=(config("queries.categories"));
        $ddArray["ROOT CAT"]=[  
            "list"=>[
                "QUERY",
                "SUB CAT1"=>["active_treatments_it","treatments_start_per_year_it"]
            ]
        ];
        $ddArray=traverse($ddArray);
        return view("content_only",["content"=>
            SVL::input("tree","treename",NULL,["style"=>"width: 150px;"],["list_source"=>$ddArray])
            .'<script>$( function() {$( "#treename" ).menu();});</script>'
        ]);
    }
         
        $data=[
            "user"=>$user,"center"=>$center,"id"=>$id,"queriesDropdown"=>array_merge([""=>""],$ddArray),
            "SVL"=>SVL::class,
            //"formatsList"=>array_merge([""=>trans("rdp.preview")],config("rdp.export_formats.dropdown")),
            "formatsList"=>config("rdp.export_formats.dropdown"),
            ];
        return view("queries/queries",$data);
    }  
    public function info(Request $request){ 
        //return[$request->wantsJson()?"Y":"N"];
        //json info for
        $result=[];
        if($request->id){
            $query=self::query($request->id, $request->options??[]);   
            $result=[
                "query"=>$query,
                "table"=>utils::db2html(["query"=>$query["data"],"class"=>"preview_table","noEncoding"=>"yes"]),
                "info"=>$query["name"]."<br>"
                .($query['description']?("(".$query['description'].")<br>"):"")  
                .($query["uf_parameters"]?($query["uf_parameters"]."<br>"):"")
                .trans("rdp.updated_to") . " ". $query["datetime"] . "; "
                .trans("rdp.results") . ":" . $query["n"]
            ];
            if ($request->wantsJson()){
                return $result;
            }else{
                //return $result["preview"];
            }        
        }
    }
    public function debug($id,$options=""){  
        $options=utils::url2array($options);//https://www.ridp.it/queries/preview/29/parameters={json}&...        
        return (self::query($id, $options));
    }    
    public function preview($id,$options=""){      
        $options=utils::url2array($options);//https://www.ridp.it/queries/preview/29/parameters={json}&...        
//return $id. "-".utils::viewStuff($options).utils::viewStuff($options["parameters"]??["no"=>"no"]);          
        $query=self::query($id, $options);
        if  (\Auth::user()->privilege>=100)return utils::viewStuff($query);   
        return view('queries/preview',["query"=>$query,"table"=>utils::db2html(["query"=>$query["data"],"class"=>"preview_table","noEncoding"=>"yes"])]);
    }  
    
    public function download($id,$options=""){ 
        $options=utils::url2array($options);//https://www.ridp.it/queries/preview/29/parameters={json}&...        
        $query=self::query($id,$options); 
        $format=$options["format"]??"html";
        $formats=config("rdp.export_formats");//various format metadata                                   
        if($query["n"]>0 && $format && array_key_exists($format,$formats["file_extension"])){// query and format are valid
            //prepares data:
            $queryName=config("queries.details.$id.name_it");        
            $data=$query["data"];
            $contentType=$formats["mime_type"][$format];
            $fileName=self::formatFileName($queryName." ".$query["datetime"],$formats["file_extension"][$format]);            
            $header=(sizeof($data)>0?array_keys((array)$data[0]):[]);
            //output:
            switch($format){             
            case "json":
                return response(json_encode($data))->withHeaders([
                    "Content-Type" => "type={$contentType}",
                    "Content-Disposition" => "attachment;filename={$fileName}",
                    "Cache-control"=>"private",
                    //"Content-type"=>"application/force-download",
                    "Content-transfer-encoding"=>"binary\n",
                ]);
                break; 
            case "hstml":
                return response(utils::db2html(["select"=>$query->sql(),"orderBy"=>$orderBy]))->withHeaders([
                    "Content-Type" => "type={$contentType}",
                    "Content-Disposition" => "attachment;filename={$fileName}",
                    "Cache-control"=>"private",
                    "Content-type"=>"application/force-download",
                    "Content-transfer-encoding"=>"binary\n",
                ]);
                break; 
            default://case "xlsx":case "ods":case "csv":case "html":case "xls":
                ////prepares headers with file name and type:
                header("Content-Type: {$contentType}");
                header("Content-Disposition: inline; filename={$fileName}");
                //prepares spreadsheet:
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()
                    ->setCreator("Registro Italiano Dialisi Pediatrica")
                    ->setLastModifiedBy("RIDP")
                    ->setTitle($queryName)
                    ->setSubject($queryName . " on ".$query["datetime"])
                    ->setDescription($query["description"])
                    ->setKeywords("RIDP query data extraction")
                    ->setCategory("RIDP query result file")                
                    ->setCompany('S.V.');  
                //prepares sheet and fills it with header and data:
                $sheet = $spreadsheet->getActiveSheet()
                    ->fromArray($header,NULL,'A1')
                    ->fromArray($data,NULL,'A2')
                    ->freezePane('A2'); 
                $sheet=self::formatSheet($sheet);
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, config("rdp.export_formats.php_office_writer")[$format]);
                $writer->save("php://output"); 
                return NULL;
            }            
        }                            
        return NULL;
    }
    
    static private function query($id="",$options=[]){
        //result=>array of results,
        //options=>[parameters=>[name=>value...],orderBy=>""]
        //takes metadata (sql and defaults) from config file and combines it with parameters        
        $metaQuery=config("queries.details.$id")??[];        
        $parameters=$options["parameters"]??[];       
        $UFparameters= [];
        $lang=(key_exists("lang",$options))?$options["lang"]:App::getLocale();
        $query=[];
        if($metaQuery){ 
            $sql=$metaQuery["sql"];
            $sql= str_replace("_*","_{$lang}",$sql);//replacement of language based columns..
            $sql=str_replace("id,","",$sql);//...SVSV ugly, check if it's even used 
            $metaParameters=$metaQuery["parameters"]??[];
            foreach($metaParameters as $k=>$v){//if no paramater set, set the default. If yes, it mentions it in the u.f.parameters
                if(!key_exists($k, $parameters)) {
                    $parameters[$k]=$v["default"];
                }                         
                if($parameters[$k]!=($v["default"]??NULL)){//u.f. parameters:
                    $listSource=utils::listOptions($v["list_source"]??[]);//if there's an option string/list, takes the list...
                    $ufValue=$listSource[$parameters[$k]]??$parameters[$k];//..if actual value found in the list, takes the u.f. value instead of the actual one
                    $UFparameters[$k]=$metaParameters[$k]["name_it"]. ": ".$ufValue;
                }
            };             
            //return $metaQuery["sql"]."<hr>". utils::viewStuff($parameters)."<hr>";
            //data: raw
            //$data=DB::select(DB::raw($metaQuery["sql"]),$parameters);
            $data=utils::DBSelect($metaQuery["sql"],$parameters,true);
            
            if(key_exists("where",$options)) $data->where($options['where']);//SVSV these two not used as of now
            if(key_exists("orderBy",$options)) $data->orderBy($options['orderBy']);// "
            //data: from collection of objects to 2D array
            $data=(array)$data;
            array_walk($data,function(&$v,$k){$v=(array)$v;});//stdClass=>array for every record
            $query=[
                //headers: name date etc
                "id"=>$id,
                "name"=>$metaQuery["name_$lang"],
                "description"=>$metaQuery["desc_$lang"]??"",
                "notes"=>$metaQuery["notes_$lang"]??"",
                "datetime"=>\Carbon\Carbon::now(),
                //inputs:sql and parameters (as array as u.f. string)
                "sql"=>$sql,"parameters"=>$parameters,"uf_parameters"=>implode($UFparameters,";"), 
                //ouput: data, count, graphs etc
                "chart"=>($metaQuery["chart"]??[]),
                "n"=>count($data),   
                "header"=> (count($data)>0)?array_keys($data[0]):[],
                "data"=>$data,                
            ];
            if($query["n"]>0 && $query["chart"]){               
                //DATATABLE definition
                $dataTable=\Lava::DataTable(); 
                //$type[,$colDesc[,$colLabel[,$format[,$role ]]]] 
                //type=date|number|string|... 
                foreach($query["chart"]["columns"] as $key=>$col){
                    $dataTable->addColumn($col); 
                };
                foreach($data as $row){ 
                    $outRow=[];
                    foreach($query["chart"]["columns"] as $key=>$col){
                        $outRow[$key]=$row[$key];
                    }
                    $dataTable->addRow(array_values((array)$outRow));
                };
                //chart options= default+custom                
                $query["chart"]["options"]=array_merge( 
                    [
                        "title"=>$query["name"].(sizeof($UFparameters)?(" (".implode("; ",$UFparameters).")"):""),
                        "titlePosition"=>"out",
                        "axisTitlesPosition"=>'out',
                        "subtitle"=>$query["description"],
                        "chartArea"=>["width"=>'70%',"height"=>'70%'],
                        "legend"=>["position"=>"in"],//NB right is incompatible with multiple vAxes 
                        //"explorer"=>["actions"=>['dragToZoom', 'rightClickToReset']],error w/piecharts
                        "selectionMode"=>"multiple",                        
                    ],
                    $query["chart"]["options"]??[]  
                );               
                //actual chart                
                $query["chart"]["object"]=\Lava::{$query["chart"]["type"]}(
                        $id,$dataTable,
                        $query["chart"]["options"]
                );
                $query["chart"]["render"]=[ 
                    "type"=>$query["chart"]["type"],
                    "id"=>$id,
                    "script"=>\Lava::render($query["chart"]["type"],$query["id"],$id),
                    "defaultDiv"=>"<div id='$id' style='overflow:visible;height:100%;'></div>",                    
                ];
            }                
        }      
        return $query;
    }  
    
    static private function formatFileName($name,$extension){
        return str_replace([",",";",":",".","/","\\"], " ",$name).".".$extension;
    }
    
    static private function formatSheet($sheet,$options=[]){//format adjustments, possible data summary
        //adjustments are essentialy on dates converted from string to number and their cells properly formatted
        $maxCols=$sheet->getHighestColumn();$maxCols++;//(can't use method return value in write context)
        $maxRows=$sheet->getHighestRow();
        for ($column = 'A'; $column != $maxCols; $column++) {//no col iterator in this version? Anyway PHPXL iterators are said to be slow
            $colType="";//stores the column type based on first not null field
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $type=null;            
            for($row=2;$row<=$maxRows;$row++){
                $cell = $sheet->getCell($column.$row); $val=$cell->getValue();
                if($val!=NULL){//type not yet set + 1st not empty cell: sets type [and column format]
                    //cell format:
                    $date=date_create_from_format("d/m/Y",$val);                    
                    if($date){
                        $cell->getStyle()->getNumberFormat()->setFormatCode('dd/mm/yyyy');
                        if($colType==""){$colType="date";}
                    }else{
                        $date=date_create_from_format("Y-m-d",$val);
                        if($date){                            
                            $cell->getStyle()->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD);
                            if($colType==""){$colType="date";}
                        }
                    }
                    if($colType=="" && is_numeric($val)){ $colType="numeric";}
                    //cell content (string=>numeric)
                    if($date){$cell->setValue(\PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($date));}
                }                                              
            }//rows loop
        }//cols loop 
        return $sheet;
    }
    
    public function showParameters($id,$options=""){//parameters html fields
        $options= (array)json_decode($options);        
        $lang=$options["lang"]??App::getLocale();
        $metaQuery=config("queries.details.$id")??[];
        $separator=$options["separator"]??"<br>";
        $arr=[];
        if($metaQuery && isset($metaQuery["parameters"])){
            foreach($metaQuery["parameters"] as $k=>$v){
                $temp=SVL::label("",$v["name_$lang"]??$k,["title"=>$v["desc_$lang"]??"-"]) .": ";
                if(isset($v["list_source"])){
                    $temp.=SVL::input("select", $k, $v["default"],[], ["list_source"=>$v["list_source"]]);
                }else{
                    $temp.=SVL::input("", $k, $v["default"]??NULL,["style"=>"min-width:3vw"]);
                }
                $arr[]=$temp;
            } 
        }
        return implode($separator, $arr);                
    }
    
}



