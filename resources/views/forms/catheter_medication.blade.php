@extends('forms.common.subforms')
@section('form-data')
    @if($parentModel!=null)  
        <div class="forms_table">
            <div class="forms_thead">
                <div class="forms_tr">
                    <div class="forms_td">{!!$newModel->label("date");!!}</div>
                    <div class="forms_td">{!!$newModel->label("disinfectant_type_1");!!}</div>
                    <div class="forms_td">{!!$newModel->label("disinfectant_type_2");!!}</div>
                    <div class="forms_td">{!!$newModel->label("every_x_days",trans("rdp.every_x_days"));!!}</div>
                    <div class="forms_td"> </div>
                </div>
            </div>    
            <div class="forms_tbody">
                @foreach($models as $model)            
                    {!!$model->openForm(['class'=>'forms_tr'])!!}
                        {!!$model->input("id"); !!}                        
                        <div class="forms_td">{!!$model->input("date",["style"=>"width:10em;"],["placeholder"=>"-"]);!!}</div>
                        <div class="forms_td">{!!$model->input("disinfectant_type_1",[],["placeholder"=>"-"]);!!}</div>
                        <div class="forms_td">{!!$model->input("disinfectant_type_2",[],["placeholder"=>"-"]);!!}</div>
                        <div class="forms_td">{!!$model->input("every_x_days",[],["placeholder"=>"-"]);!!}&nbsp;&nbsp;{{trans("rdp.days")}}</div>
                        <div class="forms_td" style="display:flex">
                            <div>
                                <button class="icon_button save" data-op="save" title="{{trans('rdp.save')}}" ></button>
                            </div><div>  
                                <button class="icon_button delete" data-op="delete" title="{{trans('rdp.delete')}}" ></button>
                            </div>
                        </div>  
                    {!!$model->closeForm()!!}     
                @endforeach           
                @if($newModel->permissions()["M"])
                <div class="forms_tr" style="text-align:left;">{{trans("rdp.add")}}</div>
                    {!!$newModel->openForm(['class'=>'forms_tr new_line'])!!}
                        {!!$newModel->input("id"); !!}
                        {!!$newModel->input("parent"); !!}
                        <div class="forms_td">{!!$newModel->input("date",["style"=>"width:10em;"]);!!}</div>
                        <div class="forms_td">{!!$newModel->input("disinfectant_type_1");!!}</div>
                        <div class="forms_td">{!!$newModel->input("disinfectant_type_2");!!}</div>
                        <div class="forms_td">{!!$newModel->input("every_x_days",["style"=>"width:3em;"]);!!}&nbsp;&nbsp;{{trans("rdp.days")}}</div>
                        <div class="forms_td">
                            <button class="icon_button add" data-op="add" title="{{trans('rdp.add')}}" ></button>
                        </div>
                    {!!$newModel->closeForm()!!} 
                @endif
            </div>
        </div> 
    @endif
@endsection