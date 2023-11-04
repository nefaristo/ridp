@extends('layouts.main')
@section('otherHeads')
@endsection
@section('title',trans('rdp.queries'))
@section('content')

    <div style="">
        <div class="ctrl_panel"  >
            <div style="display:flex;">
                <fieldset style="flex-grow:1; ">
                    <legend>{{trans("rdp.query")}}</legend>                    
                    {!!$SVL::input("select","id",null,["id"=>"query"],["list_source"=>$queriesDropdown])!!}                    
                    <span id="loading_container"></span>
                </fieldset>       
                <fieldset id="parameters_container" style="flex-grow:4;">
                    <legend>{!!trans("rdp.parameters")!!}</legend>
                    <span style="width:80%" id="parameters"></span>
                    <span style="flex-grow:1"><button id="refresh_button" title="{!!trans('rdp.update')!!}" class='icon_button refresh' style="display:none"></button></span>                                            
                </fieldset>
                <button onClick="window.open('/help/queries');" class="icon_button help" title="{{trans("rdp.popup_patient_search")}}" style="vertical-align:top"></button>
            </div>                   
        </div>   
        <div class="sv_pseudo_details">
                        
            <div class="header"><span>{!!trans('rdp.info')!!}</span></div>
            <div class="body" style="display:none">
                <div id="info_container" style="display:flex;justify-content:space-between; margin:5px auto;padding:5px auto;">
                    <span id="info" ></span>
                    <span id="download" >
                        {!!trans('rdp.download')!!} in {!!$SVL::input("select","export_format",null,["id"=>"export_format"],["list_source"=>$formatsList])!!}
                        <button id="download_button" title="{!!trans('rdp.download')!!}" class='icon_button download'></button>
                    </span>
                </div>                
            </div>                        
            <div class="header"><span>{!!trans('rdp.table')!!}</span>
            </div>
            <div class="body" style="overflow:auto;width:100%;display:none">
                <div id="table_container" style="height:10vw;overflow:auto">
                    <div id="table" ></div>
                </div>                
            </div>   
            <div class="header" onClick="$('#chart_container').trigger('resize');"><span>{!!trans('rdp.chart')!!}</span></div> 
            <div class="body" style="display:none"> 
                <div id="chart_container" style="height:22vw">
                    <div name='chart' id="-" style="width:100%;height:100%;"></div>
                    <div name='script'></div>
                </div>                
            </div>          
        </div>
        
        
        
        <script>
            $(function(){ 
                refresh_parameters();
                function refresh_parameters(){
                    if($("#query").val()){
                        //prep:
                        $("#refresh_button").css("display","none");
                        $("#loading_container").html(SVL.defaults.loading);  
                        //misc info retrieval:     
                        //actual parameters:
                        $("#parameters").load(//loads new parameters controls
                                "/queries/showParameters/" + $("#query").val() ,
                                function( response, status, xhr ) {
                                    if ( status == "error" ) {
                                        console.log("error" + xhr.status + " " + xhr.statusText );
                                        $("#parameters").html(xhr.responseText);
                                        $("#loading_container").html("");
                                    }else{
                                        $("#refresh_button").css("display",($("#parameters").children().length>0)?"inline-block":"none");
                                        $("#loading_container").html("");
                                    }
                                    refresh_info();
                                }
                            );                      
                    }else{
                        $("#parameters").html("");
                        refresh_info();//immediate, hides containers etc
                    }                    
                }
                function refresh_info(){SVL.defaults
//$("#info_container").css("display","none");$("#info_container").parent().css("display","none");
                    $('#table_container').css("display","none");$("#table_container").parent().css("display","none");
                    $('#chart_container').css("display","none");$("#chart_container").parent().css("display","none");
                    if($("#query").val()){
                        ajax(
                            {
                                url:"/queries/info",//+url_parameters(),
                                contentType: "json",//sent. dataType: 
                                dataType:"json",//requested.NB if defined, ajax throws an error for a 200 response of the wrong type
                                data:{
                                    id:$('#query').val(),
                                    options:{parameters:$('#parameters *').serializeObject(),}                                
                                },
                                done:function(data){
                                    //info visibility & content:
                                    $("#info_container").css("display","flex");
                                    $(".sv_pseudo_details .header").eq(0).trigger("click");
                                    $("#info").html(
                                            "<b>" + data.query.name + "</b>"
                                            +(data.query.description?("<br><i>"+data.query.description + "</i>"):"") 
                                            +(data.query.notes?("<br><div style='font-size: 80%;border:1px solid gray;border-radius:5px;'>"+data.query.notes + "</div>"):"") 
                                            +"<hr>parametri usati:<br>"
                                            +(data.query.uf_parameters?('<!--{!!trans("rdp.parameters")!!}:-->'+ data.query.uf_parameters +"<hr>"):"")
                                            + '{!!trans("rdp.results")!!}' + ":" + data.query.n + " (" + '{!!trans("rdp.updated_to")!!}' + " " + moment(data.query.datetime.date).format('lll') + ")"
                                            );
                                    $("#download").css("display","inline");
                                    //preview table visibility & content:
                                    $('#table_container').css("display","block") 
                                    $("#table").html(data.table); 
                                    if(data.query.chart.length==0)$(".sv_pseudo_details .header").eq(1).trigger("click");
                                    //chart visibility & content:
                                    if(data.query.chart.length!==0){
                                        $('#chart_container').css("display","block");
                                        $("#chart_container div[name='chart']").attr("id",data.query.chart.render.id);
                                        $("#chart_container div[name='script']").html(data.query.chart.render.script);
                                        $(".sv_pseudo_details .header").eq(2).trigger("click");
                                    }
                                },
                            },//end parameters
                            {$output:$('#info'),debug:true,}//options
                        )   
                    };
                }           
                function ajax(parameters,options){
                    //parameters are the ones of $.ajax,
                    //options defaults:
                    defOpt={
                        $output:null,   //$ obj on which to write loading animations + response[|errors] 
                        debug:false,    //writes errors onto $output
                        log:true,       //writes onto console log                        
                        loading:SVL.defaults.loading,
                    }
                    options=$.extend(defOpt,options);                        
                    if(options.$output){
                        backup=options.$output.html();
                        options.$output.html(options.loading);
                    }      
                    if(options.log){console.log("parameters",parameters);console.log("options",options);}                          
                    $.ajax(parameters)    
                    .done(function(data){  
                        if(this.done){
                            this.done(data);
                        }else{//default:
                            if(options.log){console.log("default response");}
                            if(options.$output){//dataTypes can be ["text","json|html"] so checks the presense of json in the array:
                                options.$output.html((this.dataTypes.indexOf("json") == -1)?data:JSON.stringify(data))
                            }
                        }
                        if(options.log){console.log("data:",data);console.log("metadata:",this);}
                    })
                    .fail(function(jqXHR, textStatus, errorThrown){
                        if(options.$output){
                            options.$output.html((options.debug)?jqXHR.responseText:backup);
                        }
                        if(options.log){
                            console.log("responseText:",jqXHR.responseText);                        
                            console.log("errorThrown:",errorThrown);
                            console.log("textStatus:",textStatus);                        
                            console.log("xhr:",jqXHR);
                        }
                    })
                }

                function url_parameters(){                    
                    id=$("[name='id']").val();
                    format=$("[name='export_format']").val();
                    parameters={};
                    $("#parameters").children().each(function(){
                        if($(this).attr('name')){parameters[$(this).attr('name')] = $(this).val();}
                    });
                    return "/"+id+"/format="+encodeURIComponent(format)+"&parameters="+encodeURIComponent(JSON.stringify(parameters));                    
                }
                
                //DOM EVENTS
                //fills parameters w/ default values:
                $("#query").on('change',function(e){ 
                    refresh_parameters();
                }); 
                //updates query general info & preview table:
                $("#model_container").on("click","button",function($this){
                    switch ($(this).attr("id")){
                    case "download_button":
                        url_params=url_parameters();
                        location.href="/queries/download"+url_params;                        
                        break;
                    case "refresh_button":
                        refresh_info();
                        break;
                    case "table_button":
                        //$("#table").toggle(SVL.defaults.toggleTime,function(){$(this).parent().trigger("resize");});    
                        break;
                    case "chart_button":
                        //$("[name='chart']").toggle(SVL.defaults.toggleTime,function(){$(this).parent().trigger("resize");});
                        break; 
                    }                    
                })
                //export
                $("#table_container").draggable().resizable();
//$("#chart_container").draggable().resizable();
                $("#chart_container").resize(function(){
                    lava.redrawCharts();
                })        
            })
        </script>
    </div>
@endsection