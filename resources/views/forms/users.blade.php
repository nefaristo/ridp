@extends('layouts.main')
@section('content')
    <title>@lang("rdp.users")</title>
    <?php $firstModel=$models[0];?>
    <div id="mainform_container" class="forms_table">
        <div class="forms_thead">
            <div class="forms_tr">
                <div class="forms_td">{!!$firstModel->label("username");!!}</div>
                <div class="forms_td">{!!$firstModel->label("email");!!}</div>
                <div class="forms_td">{!!$firstModel->label("privilege");!!}</div>
                <div class="forms_td">{!!$firstModel->label("center");!!}</div>
                <div class="forms_td">{!!$firstModel->label("center",trans("rdp.city"));!!}</div>                
                <div class="forms_td">{!!$firstModel->label("active");!!}</div>
                <!--<div class="forms_td">{!!$firstModel->label("mailing_list");!!}</div>-->
                <div class="forms_td"></div> 
            </div>
        </div>    
        <div class="forms_tbody">            
            @foreach($models as $model)            
                {!!$model->openForm(['class'=>'forms_tr'])!!}
                    <div class="forms_td">{!!$model->input("id"); !!}{!!$model->input("username",[],["placeholder"=>"?"]);!!}</div>
                    <div class="forms_td">{!!$model->input("email",[],["placeholder"=>"?"])!!}</div>
                    <div class="forms_td">{!!$model->user()->UFPrivilege()!!}</div>                    
                    <div class="forms_td">
                        @if($model->parentModel()!=NULL)
                            {!!substr($model->parentModel()->title(),0,50)."..."!!}
                        @endif
                    </div>
                    <div class="forms_td">
                        @if($model->parentModel()!=NULL)
                            {!!$model->parentModel()->city!!}
                        @endif
                    </div>                    
                    <div class="forms_td">{!!$model->input("active");!!}</div>
                    <!--<div class="forms_td">{!!$model->input("mailing_list",["onClick"=>"return postIt($(this));"]);!!}</div>-->
                    <div class="forms_td">
                        <!--<input type="image" src="/images/save.png" data-toggle="tooltip" title="salva" class="icon_button show_changed">-->
                    </div>
                {!!$model->closeForm()!!}     
            @endforeach 
        </div>
    </div>  
    <script>
        $("form").on("change","input:image,input[name='active']",function(e){
            //e.preventDefault();e.stopPropagation();
            var mainform_params={
                container:$(this).closest("form"),
                objMethod:"save",
                $statusBox:$(this),
                //afterDone:function(data){location.reload();},
            };
            console.log("mainforms",mainform_params);
            return RDP.update(mainform_params);            
        });
    </script> 
@endsection