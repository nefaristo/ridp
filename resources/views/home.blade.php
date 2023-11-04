@extends('layouts.main')
@section('otherHeads')
@endsection
@section('content')   
<div style="font-size:100%;background-color: rgb(255,255,0);text-align:center"><b > 
        NB il sito è correntemente in manutenzione.Alcune funzioni potrebbero non essere disponibili.</b>
</div>
    @if($user)   
@if($user->privilege>=1)    
        <div class="sv_pseudo_details">
            <div class="header">
                <span>
                    @lang("rdp.center_management"):             
                    @if($user->privilege>=10)
                        @lang("rdp.all_centers")<br>
                    @elseif ($center)
                        {{$center->code}}-{{$center->title()}}<br>                                       
                    @endif 
                </span>
            </div>
            <div class="body" id="datacheck_panel"  style="display:'block';">
                <!--
                {{trans("rdp.data_check")}}
                <a id="datacheck_refresh" href=''>
                    {{trans("rdp.update")}}
                </a>
                -->
                <div id="datacheck_window"></div>
            </div> 
        </div> 
@endif
        <div class="sv_pseudo_details">
            <div class="header"><span >{{trans("rdp.user") . " '". \Auth::user()->username . "'"}}</span></div>
            <div class="body" id="user_panel" style="display:{{$user->privilege!=10?'block':'none'}}">   
                email: {{\Auth::user()->email}}<br>  
                @lang("rdp.level") : {{\Auth::user()->UFPrivilege()}}<br>
                @lang("rdp.session_id") : {{session("id","-")}}<br>
                <!--@lang("rdp.user") : {{session("user","-")}}<br>
                @lang("rdp.center") : {{session("center","-")}}<br>-->
                @lang("rdp.language") : {{\Auth::user()->lang}}<br>   
                @if($user->privilege<10)
                    {{link_to('password/reset', "reset password")}}
                @endif
            </div>
        </div>
        
        @if($user->privilege>=10)                 
            <div class="sv_pseudo_details">
                <div class="header"><span class="superuser_only">registro</span></div>
                <div class="body" id="superuser_panel" style="display:{{$user->privilege>=10?'block':'none'}};">
                    <ul>
                        <li>{{link_to("/sessions/{$logLimit}", str_replace("{n}",$logLimit,trans("rdp.last_log_entries")))}}</li>
                        <li>{{link_to('/queries/preview/100', trans("rdp.main_tables_count"))}}</li>                         
                        <li>{{link_to('password/reset', trans("rdp.send_reset_to_mail"))}}</li>
                        <li><a href="temp/Interrogazioni%203_0.html">Interrogazioni versione precedente</a></li> 
                    </ul>                    
                    <div id="superuser_window"></div>                    
                </div>
            </div>
        @endif
        @if($user->privilege>=100)
            <div class="sv_pseudo_details">
                <div class="header"><span class="admin_only">admin</span></div>
                <div class="body" id="admin_panel">  
                    <ul>                       
                        <li><a href="/systeminfo">System info</a></li>                       
                        <li><a href="/warnings">Warnings</a></li>
                        <li><a href="/code_translation">Utilità codifica pazienti (temporaneo)</a></li>
                        <li><a href="/view-clear">Clear view cache</a></li>
                        <li><a href="/route-clear">Clear route cache</a></li>
                        <li><a href="/config-cache">Clear config cache</a></li>
                        <li><a href="/clear-cache">Clear facade cache</a></li>
                        <li><a href="/optimize">Reoptimize class loader</a></li>                                                
                        <!--<li><a href="/route-cache">Route cache</a></li>-->                        
                    </ul>
                    <div id="admin_window"></div>
                </div>
            </div>        
        @endif 
        <script>
            $(function(){                
                $("#admin_panel").on("click","a",function(e){
                    e.preventDefault(); e.stopPropagation();  
                    $("#admin_window").html(SVL.defaults.loading);
                    $("#admin_window").load($(this).attr("href"));
                });
                $("#superuser_panel").on("click","a",function(e){
                    e.preventDefault(); e.stopPropagation();  
                    $("#superuser_window").html(SVL.defaults.loading);
                    $("#superuser_window").load($(this).attr("href"),
                    function (responseText, textStatus, XMLHttpRequest) {
                    $("#superuser_window").html(responseText);}); 
                }); 
                $("#datacheck_refresh").on("click",function(e){
                    e.preventDefault(); e.stopPropagation();  
                    loadDatacheck('{"refresh":true}');
                    return false;
                });
                loadDatacheck();
                function loadDatacheck(args=""){
                    $("#datacheck_window").html(SVL.defaults.loading);
                    $.ajax('/datacheck/'+args)
                        .done(function(data){
                            $("#datacheck_window").html($(data).find("#log"));
                        })
                        .fail(function(xhr,status){
                            //console.log(xhr);
                            $("#datacheck_window").html(xhr.responseText);
                        });
                }                
            })
        </script>        
    @endif    
    
    <!--begin temp-->
        @if($user->privilege>=100)
            <div class="sv_pseudo_details">
                <div class="header"><span>{{trans("rdp.patients_encoding")}}</span></div>
                <div class="body" style="display:none;" >                 
                    <div id="help_encode" class="main_box" style="font-size: 90%">
                        Questa è la <b>tabella di traduzione</b> dal vecchio al nuovo codice 
                        dei pazienti <i>già immessi</i>,
                        <b>disponibile per un periodo iniziale di qualche settimana</b>; 
                        si consiglia di scaricarla e conservarla in luogo sicuro 
                        per consentire anche in futuro di risalire dal nuovo codice anonimo al vecchio codice 
                        in caso di necessità di controllare i dati del paziente.<br>
                        Per i pazienti immessi <i>da ora in avanti</i>, 
                        nessuna informazione per cui sia possibile risalire all'identità del paziente
                        sarà conservata sul database: 
                        sarà cura di ogni centro creare e conservare l'eventuale tabella di traduzione.<br>
                        <button onclick="window.open('/help/privacy');" class="icon_button help" title="ricerca per cognome + nome + data di nascita" style="vertical-align:top"></button>
                    </div>
                    <div style="border:1px grey dotted;border-radius: 5px;text-align: center;margin: 10px auto">
                        <?php
                           $formats=["xls"=>"xls (Excel)","ods"=>"ods (OpenOffice)","csv"=>"csv (Comma Separated Values)","html"=>"html","json"=>"json"];
                            $links=trans("rdp.export").":";
                            foreach($formats as $k=>$v){
                                $links.="&nbsp;<a href='\code_translation_table/{$k}'>{$v}</a>&nbsp;|&nbsp;";
                            } 
                        ?>            
                        {!!$links!!}
                    </div>
                    <div id="code_translation_table" style="display:block;width:90%;height:30vh;overflow:scroll;margin:0px auto;padding: 4px 4px 4px 4px;border:1px solid grey;border-radius: 2px"></div>
                </div>
            </div>
            <script>
                $(function(){    
                    $("#code_translation_table").html(SVL.defaults.loading);
                    $("#code_translation_table").load("/code_translation_table");
                })                
            </script>
        @endif    
    <!--end temp-->
    
@endsection
