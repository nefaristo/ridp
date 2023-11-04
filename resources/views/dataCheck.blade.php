@extends('layouts.main')
@section('otherHeads')
@endsection
@section('title',trans('rdp.data_check'))
@section('content')
<div style="height:85vh;display:flex;flex-direction:column ;flex-wrap:nowrap;justify-content:flex-start;">    
    <div class="ctrl_panel" id="filter_box">-
        <table>
            <tr>
                <td>{{trans("rdp.type")}}</td>
                <td >
                    {!!Form::select("type",$data["typeSelect"],$data["type"],["id"=>"filter_type"]);!!}
                </td>
                <td><button>{{trans("rdp.refresh")}}</button></td>
                @if($data["user"]->privilege>=10)
                <td>
                    <details>
                        <summary>sql</summary>
                        <div id="sql"></div>
                    </details>
                </td>
                @endif
            </tr>
        </table>
    </div>
    <div style="order:1; display: flex;flex-direction: row;align-items: stretch;justify-content: space-start;">
        <div id="mainlist">
        </div>
    </div>
</div>
<script>  
    $(function(){ 
        $("#mainlist").resizable();        
        $("#filter_box")
            //.on("change",function(e){loadTable();})
            .on("click",":button",function(e){loadTable();}); 
        $("#mainlist").on("click.mainlist","a",function(e){//redirects links from the list into the detail element
            $(this).attr('target','_blank');return true;//this line is the alternative 
            e.preventDefault();
            $("#mainform_target").html(SVL.defaults.loading);
            $("#mainform_target").load($(this).attr("href") + " #mainform_container",
                function( response, status, xhr ) {
                    if(status=="error"){$( "#mainform_target" ).html( "error: " + xhr.status + " " + xhr.statusText );}
            });
        });
        function loadTable(){
            if($("#filter_type").val()){
                $('#mainlist').resizable('destroy');//needed to make the re-resizable work in jqueryui
                $("#mainlist").html(SVL.defaults.loading);
                url='{{url("datacheck/errorList")}}/'+$("#filter_type").val();                
                if($("#filter_center").val()){
                    url+="/"+JSON.stringify({'where':JSON.stringify({'centro':$("#filter_center").val()})})
                };
                $("#mainlist").load(url,
                    function( response, status, xhr ) {
                        if(status=="error"){
                            $( "#mainlist" ).html( "error: " + xhr.status + " " + xhr.statusText + "<br>"+response);
                            $('#mainlist').resizable();
                            $('#sql').html("error");
                        }else{
                            $('#mainlist').resizable();
                            if($('#sql')){//debug/admin info  
                                $('#sql').load('{{url("datacheck/sql")}}/'+$("#filter_type").val(),
                                    function( response, status, xhr ){  
                                        if(status!="error")$('#sql').html($('#sql').html().replace((/(\r\n|\n|\r)/g,"<br />")));
                                    }
                                );  
                            }
                        }
                });            
            }else{
                $("#mainlist").html("");
            }
        }
        loadTable();
    });
</script>
@endsection