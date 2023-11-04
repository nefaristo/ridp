@extends('layouts.main')
@section('content')
    <div id="mainform_container" style="width:99%;overflow:auto">
    <script>
        //default update parameters (global scope to get into all $(function()) blocks
        var mainform_params={
            container:"#mainform_container form",
            //filter:'*:not(#subform_container,#subform_container *)',//either updated by their own buttons, cascaded or done by some override of params.afterDone            
            afterDone:function(data){
                if(data.ops[0].objMethod=="D"){
                    $("#mainform_container").delay(RDP.infoTypes.messages.TTL).fadeOut(RDP.settings.fadeTime,function(){$(this).remove();});
                }
                if(typeof MainTree!="undefined")MainTree.refresh();
            },
        }
        $(function(){
            //DEFAULT SETTINGS for save/delete:            
            //STANDARD BUTTONS BEHAVIOUR 
            $("#mainform_container").on('click.update','.icon_button',function(e){
                e.preventDefault(); e.stopPropagation();                
                if($(this).closest("#subform_container").length==0){//TODO check if it's directly selectable in the event
                    var objMethod=$(this).attr('data-op'); 
                    if(objMethod){
                        mainform_params.objMethod=objMethod;
                        mainform_params.$statusBox=$(this);
                        mainform_params.afterDone=function(data){                            
                            if (data.ops.length>0){
                                var model=data.ops[0].model;
                                if(data.ops[0].objMethod=="D"){//delete: remove all content (after time for message                                    
                                    $("#mainform_container").delay(RDP.infoTypes.messages.TTL).fadeOut(RDP.settings.fadeTime,function(){$(this).html("");});
                                }else{//add/save: reload the whole content (for loading possible subforms, typically after adding)
                                    $.get("/edit/"+ data.ops[0].info.shortClass.toLowerCase() + "/" + model.id,
                                          function(data){$("#mainform_container").replaceWith($(data).find("#mainform_container"));}
                                    );
                                }
                                if(typeof MainTree!="undefined")MainTree.refresh();
                            }
                        };                      
                        return RDP.update(mainform_params);
                    }
                }
            });
            $("#mainform_container").off('click.breadcrumbs');
            $("#mainform_container").on('click.breadcrumbs','.breadcrumbs a.within',function(e){
                e.preventDefault(); e.stopPropagation();  
                $("#mainform_container").html(SVL.defaults.loading);
                $.get($(this).attr("href"),
                    function(data){$("#mainform_container").replaceWith($(data).find("#mainform_container"));}
                );
            });
        })
    </script> 
    @if ($model!=null) 
        {!!$model->openForm()!!}
        {!!$model->input("id");!!}         
        <div class='form-header'> 
            <div style="">
                @if(Auth::user()->privilege>=1)
                    @if (Auth::user()->privilege>=100)
                    <details>
                        <summary>DEBUG</summary>                        
                        {!! \App\SVLibs\utils::viewStuff($model->debug())!!}
                    </details>
                    @endif
                    <button style="display:inline-block" title="breadcrumbs" class="breadcrumbs_button" onClick="$('.breadcrumbs_popup').toggle(SVL.defaults.toggleTime);return false;"></button>
                    <div style="display:inline-block">
                        <div class="breadcrumbs_popup" >
                            <ul class="breadcrumbs"> 
                                @foreach(array_reverse($model->ancestors()) as $key=>$ancestor)
                                    <li><a class="within" href='{{"/edit/".$ancestor->info("class")."/".$ancestor->id}}'>
                                        {!!$ancestor->info("title")!!} 
                                    </a></li>
                                @endforeach
                                <li style="color:black; ">{!!$model->info("title")!!}</li>
                                @if($model->descendants()) 
                                    <ul class="breadcrumbs">
                                        @foreach($model->descendants() as $key=>$childType)
                                            @if(isset($childType["listOptions"]["hideInBreadcrumbs"]) && $childType["listOptions"]["hideInBreadcrumbs"])                                        
                                            @else
                                                <li> 
                                                    <a href="" onClick="$(this).parent().find('ul').toggle(SVL.defaults.toggleTime);return false;">
                                                        {!!trans("rdp.".$childType["config"]["names"])!!}
                                                    </a>
                                                    ({!!$childType["models"]->count()!!})
                                                    <ul class="breadcrumbs" style="display:none">
                                                        @foreach($childType["models"] as $k=>$child)
                                                            <li><a class="within" href='{{"/edit/".$child->info("class")."/".$child->id}}'>
                                                                {!!$child->info("id")!!}
                                                            </a></li> 
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif
                                        @endforeach 
                                    </ul>
                                @endif
                            </ul>
                    </div></div>
                @endif
            </div> 
            <div style="width:82%;">
                <h3>{{$model->formTitle()}}</h3>
            </div>
            <div>
                @if($model->permissions()["M"])
                        <button class="icon_button save" data-op="save" title="{{trans('rdp.save')}}" ></button>
                @endif
                @if($model->permissions()["D"])
                        <button class="icon_button delete" data-op="delete" title="{{trans('rdp.delete')}}" ></button>
                @endif
            </div>
        </div>
        <div style="text-align:center;width:100%">
            <div class="sv_allInfo" style="width:50%;margin:5px auto"></div>
        </div>
        <div class='form-content'>
            @yield('form-data')
        </div>
        <div class='form-footer'>
            
        </div>
        {!!$model->closeForm()!!}                    
    @endif
    @yield('subforms')
    </div>
    
@endsection
