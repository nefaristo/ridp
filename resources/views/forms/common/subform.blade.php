@extends('layouts.main_no_header')
@section('content') 
    <div id="subform_container">
        @if ($model!=null)
            <script>
                //default update parameters (global scope to get into all $(function()) blocks
                var subform_params={
                    container:"#subform_container form",
                    //filter:'*:not(#subform_container,#subform_container *)',//either updated by their own buttons, cascaded or done by some override of params.afterDone
                    class:"{{$model->shortClass()}}",
                    afterDeleteMessage:'{{trans('rdp.deleted')}}',                    
                } 
                $(function(){
                    //DEFAULT SETTINGS for subform save/delete:
                    $("#subform_container").on('click','.icon_button',function(e){ 
                        e.preventDefault();e.stopPropagation();                        
                        var objMethod=$(this).attr('data-op');    
                        if(objMethod){
                            subform_params.objMethod=objMethod;
                            subform_params.$statusBox=$(this);
                            console.log("subform",subform_params);                            
                            return RDP.update(subform_params);
                        }
                    }) 
                    //if completion bar is present it's updated at every change:
                    $("#subform_container").on("change.completion",".sv_input",function(e){
                        if($(".completion_bar").length>0){
                        $.post('/modelMethod/{{$model->shortClass()}}/htmlFormCompletion',$(this).closest('form').serialize())
                            .done(function(data){$(".completion_bar").replaceWith($(data));})
                            .fail(function(data){RDP.message(data.responseText,{title:"formCompletion"});})
                        }
                    })
                })
            </script>        
            {!!$model->openForm()!!}
            {!!$model->input("id"); !!}  
            <div class='subform-header'>
                <div style="display:flex;flex-direction:row-reverse;">  
                    <div>
                        <button class="icon_button delete" data-op="delete" title="{{trans('rdp.delete')}}" ></button>
                    </div><div>  
                        <button class="icon_button save" data-op="save" title="{{trans('rdp.save')}}" ></button>
                    </div>
                </div>                   
            </div>          
            <div class='subform_body' style="width:100%;overflow:auto">
                @yield('form-data')
            </div>                     
            <div class='subform_footer'>
                 
            </div>
            {!!$model->closeForm()!!}
        @endif
    </div>
@endsection
