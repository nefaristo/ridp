@extends('layouts.main')
@section('title',trans('rdp.statistics'))
@section('content')
    <div style="height:85vh;display:flex;flex-direction:column ;flex-wrap:nowrap;justify-content:flex-start;">
        <div id="statList">
            @foreach($content["ddArray"] as $k=>$v)
                <div class="sv_pseudo_details" >
                    <div class="header">{!!trans("rdp.stats.".$k)!!}<div class="statId" style="display:none">{!!$k!!}</div></div>
                    <div class="body" style="display:none"></div>
                </div>
            @endforeach        
        </div>
        <div id="stat_container" style="overflow:visible"></div> 
    </div>
    <script>
        $(function(){    
            $(".sv_pseudo_details").on('click',".header",function(){//change link/visualization 
                $(this).parent().find(".body").html(SVL.defaults.loading); 
                $(this).parent().find(".body").load(
                    "/stats/stat/" + $(this).find(".statId").html() ,
                    function( response, status, xhr ) {
                        if ( status == "error" ) {
                            $( "#stat_container" ).html( "error" + xhr.status + " " + xhr.statusText );
                        }
                    }
                ); 
            });  
        })
    </script>
@endsection