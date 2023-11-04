@extends('layouts.main_no_header')
@section('content')
    <div id="mainform_container">
        <div class='mainform-content'>
            @yield('form-data')
            <script>            
                $(function(){ 
                    //default update parameters (global scope to get into all $(function()) blocks
                    var mainform_params={
                        class:"{{$newModel->shortClass()}}",
                        afterDeleteMessage:'{{trans('rdp.deleted')}}',                        
                    }   
                    //DEFAULT SETTINGS for mainform save/delete:
                    $("#mainform_container .icon_button").on('click',function(e){
                        e.preventDefault();e.stopPropagation();                        
                        var objMethod=$(this).attr('data-op');    
                        if(objMethod){
                            mainform_params.container=$(this).closest("form");
                            mainform_params.objMethod=objMethod;
                            mainform_params.$statusBox=$(this);
                            if(objMethod=="add"){//refresh all                         
                                mainform_params.afterDone=function(data){
                                    $.get('/edits/{{$newModel->shortClass()}}/{{$newModel->parent}}')
                                    .done(function(data){
                                        $("#mainform_container").replaceWith($(data).find("#mainform_container"));
                                        SVL.reset($("#mainform_container"));
                                    });                    
                                }; 
                            }
                            console.log("mainforms",mainform_params);
                            return RDP.update(mainform_params);
                        }
                    }) 
                })
            </script>            
        </div>
    </div>
@endsection
