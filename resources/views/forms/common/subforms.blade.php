@extends('layouts.main_no_header')
@section('content')
    <div id="subform_container">
        @yield('form-data') 
        <script>            
            $(function(){ 
                //default update parameters (global scope to get into all $(function()) blocks
                var subform_params={      
                    container:"#subform_container form",
                    class:"{{$newModel->shortClass()}}",
                    afterDeleteMessage:'{{trans('rdp.deleted')}}',                    
                } 
                //DEFAULT SETTINGS for subform save/delete:
                $("#subform_container").on('click','.icon_button',function(e){
                    e.preventDefault();e.stopPropagation();                    
                    var objMethod=$(this).attr('data-op');                       
                    if(objMethod){
                        subform_params.container=$(this).closest("form");
                        subform_params.objMethod=objMethod;
                        subform_params.$statusBox=$(this);
                        if(objMethod=="add"){//refresh all                         
                            subform_params.afterDone=function(data){
                                $.get('/edits/{{$newModel->shortClass()}}/{{$newModel->parent}}')
                                .done(function(data){
                                    $("#subform_container").replaceWith($(data).find("#subform_container"));
                                    SVL.reset($("#subform_container"));
                                });                    
                            }; 
                        }
                        console.log("subforms",subform_params);
                        return RDP.update(subform_params);
                    }
                }) 
            })
        </script>            
    </div>
@endsection
