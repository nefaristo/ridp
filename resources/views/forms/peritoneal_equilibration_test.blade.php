@extends('forms.common.mainforms')
@section('form-data')
    @if($parentModel!=null)  
        <div style="text-align:center ">
            <span class="subtitle">
                {{trans("rdp.pets")}} ({{$parentModel->absoluteTitle(2,true,"-")}})
            </span>
        </div>             
        <div class="forms_table">
            <div class="forms_thead">
                <div class="forms_tr">
                    <div class="forms_td">{!!$newModel->label("date");!!}</div>
                    <div class="forms_td">{!!$newModel->label("dp_creatinine_2h");!!}</div>
                    <div class="forms_td">{!!$newModel->label("dd_glucose_2h");!!}</div>
                    <div class="forms_td">{!!$newModel->label("dp_creatinine_4h");!!}</div>
                    <div class="forms_td">{!!$newModel->label("dd_glucose_4h");!!}</div>
                    <div class="forms_td"> </div>
                </div>
            </div>    
            <div class="forms_tbody">
                @foreach($models as $model)  
                    {!!$model->openForm(['class'=>'forms_tr'])!!}
                        {!!$model->input("id"); !!}
                        <div class="forms_td">{!!$model->input("date",[],["placeholder"=>"?"]);!!}</div>
                        <div class="forms_td">{!!$model->input("dp_creatinine_2h",["style"=>"width:5em"],["placeholder"=>"?"]);!!}</div>
                        <div class="forms_td">{!!$model->input("dd_glucose_2h",["style"=>"width:5em"],["placeholder"=>"?"]);!!}</div>
                        <div class="forms_td">{!!$model->input("dp_creatinine_4h",["style"=>"width:5em"],["placeholder"=>"?"]);!!}</div>
                        <div class="forms_td">{!!$model->input("dd_glucose_4h",["style"=>"width:5em"],["placeholder"=>"?"]);!!}</div>
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
                    {!!$newModel->openForm(['class'=>'forms_tr new_line'])!!}
                        {!!$newModel->input("id"); !!}
                        {!!$newModel->input("parent"); !!}
                        <div class="forms_td">{!!$newModel->input("date");!!}</div>
                        <div class="forms_td">{!!$newModel->input("dp_creatinine_2h",["style"=>"width:5em"]);!!}</div>
                        <div class="forms_td">{!!$newModel->input("dd_glucose_2h",["style"=>"width:5em"]);!!}</div>
                        <div class="forms_td">{!!$newModel->input("dp_creatinine_4h",["style"=>"width:5em"]);!!}</div>
                        <div class="forms_td">{!!$newModel->input("dd_glucose_4h",["style"=>"width:5em"]);!!}</div>
                        <div class="forms_td">
                            <button class="icon_button add" data-op="add" title="{{trans('rdp.add')}}" ></button>
                        </div>
                    {!!$newModel->closeForm()!!} 
                @endif
            </div>
        </div>
    @endif
@endsection