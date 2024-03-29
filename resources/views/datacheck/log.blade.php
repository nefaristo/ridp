@extends('layouts.main')
@section('content')
<div id="log" style="display:flex;flex-direction:column ;flex-wrap:nowrap;justify-content:flex-start;">    
    <div style="padding: 10px">
        @foreach($data as $rec)  
            <?php 
                switch($rec["dc"]->level){
                    case "W":$styleLevel="background-color: rgb(250,250,0,0.4);;";break;
                    case "E":$styleLevel="background-color: rgb(205,0,0,0.2);";break;
                    default:$styleLevel="background-color: rgb(0,255,0,0.4);";
                }
            ?>
            <div>
                <div class="ctrl_panel" style="border:1px; border-style: outset; display: flex;flex-direction: row;align-items: stretch;justify-content: space-start;{{$styleLevel}}">
                    {!!$rec["dc"]->name_it !!}
                     ({!!sizeof($rec["log"]) !!})
                    <div onClick="console.log($(this).parent().next().toggle());" class="icon_button rightarrow" ></div>
                    <div style="margin-left:auto; margin-right:0px" class="icon_button refresh" data-id="{!!$rec['dc']->id!!}" title="aggiorna il data check"></div>
                </div> 
                <div  style="display:none">
                    @foreach($rec["log"] as $k=>$logEl)
                        {!!$logEl->text_it!!}<hr>
                    @endforeach 
                </div>
            </div>                
        @endforeach
    </div>
    <script>
        $(function(){ 
            $(".refresh").on("click.refresh");
            $(".refresh").on("click.refresh",function(e){   
                console.log($(this),"/datacheck/"+JSON.stringify({refresh:$(this).attr("data-id")}));
                $("#log").html(SVL.defaults.loading);
                $("#log").load("/datacheck/"+JSON.stringify({refresh:$(this).attr("data-id")}) + " #log", 
                    function(response,status,xhr){
                        if ( status == "error" ) {$( "#model_body" ).html(response);}
                    }
                );        
            }); 
            $(".refresh").tooltip();
        })
    </script>
</div>
@endsection