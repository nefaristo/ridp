@extends('layouts.main_content_only')
@if(isset($query))
    <div class="ctrl_panel">
        @if($query["chart"])
            <button id="table" class='icon_button table'></button>
            <button id="graph" class='icon_button graph'></button> 
        @endif
    </div>
    <div> 
        <div id="table_container" class="ui-draggable ui-resizable" style="background-color: white;position:absolute;display:block">
            {!!$table!!}
        </div>
        <div id="graph_container" class="ui-draggable ui-resizable" style="width:50vw;height:50vh;border:1px dotted gray;border-radius:5px;box-shadow: 3px 3px 3px 3px;  padding:0px; margin:0px auto; position:absolute;display:none">
            @if($query["chart"] && isset($query["chart"]["render"]))
                <div id='{!!$query["chart"]["render"]["id"]!!}' style="width:100%;height:100%"></div>
                {!!$query["chart"]["render"]["script"]!!}              
            @endif
        </div>
    </div>
    <script>
        $(function(){ 
            $("#graph_container").draggable().resizable();
            $("#graph_container").resize(function(){
                lava.redrawCharts();
            })        
            $(".ctrl_panel button").off("click");
            $(".ctrl_panel button").on("click",function(){
                $container=$("#"+$(this).attr("id")+"_container");
                $container.toggle(SVL.defaults.toggleTime,function(){$container.trigger("resize");});            
            });
        });
    </script>
@endif




