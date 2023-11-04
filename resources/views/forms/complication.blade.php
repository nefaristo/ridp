@extends('forms.common.mainforms')
@section('form-data')
    @if($parentModel!=null)  
        <div style="text-align:center ">
            <span class="subtitle">
                {{trans("rdp.complications")}} {{$parentModel->absoluteTitle(2,true,"-")}}                  
            </span>
        </div>    
        <div class="forms_table">
            <div class="forms_thead">
                <div class="forms_tr">
                    <div class="forms_td">{!!$newModel->label("date");!!}</div>
                    <div class="forms_td">{!!$newModel->label("description");!!}</div>
                    <div class="forms_td">{!!$newModel->label("hospitalization_days");!!}</div>
                    <div class="forms_td">{!!$newModel->label("dialysis_related");!!}</div>
                    <div class="forms_td"> </div>
                </div>
            </div>    
            <div class="forms_tbody">
                @foreach($models as $model)       
                    {!!$model->openForm(["class"=>"forms_tr"])!!}
                        {!!$model->input("id"); !!}                  
                        <div class="forms_td">{!!$model->input("date",[],["placeholder"=>"?"]);!!}</div>
                        <div class="forms_td">{!!$model->input("description",[],["placeholder"=>"-"]);!!}</div>
                        <div class="forms_td">{!!$model->input("hospitalization_days",[],["placeholder"=>"-"]);!!}</div>
                        <div class="forms_td">{!!$model->input("dialysis_related",[],["placeholder"=>"?"]);!!}</div>
                        <div class="forms_td" style="display:flex">
                            <div>
                                <button class="icon_button save" data-op="save" title="{{trans('rdp.save')}}" ></button>
                            </div><div>  
                                <button class="icon_button delete" data-op="delete" title="{{trans('rdp.delete')}}" ></button>
                            </div>
                        </div>  
                    {!!$model->closeForm()!!}     
                @endforeach           
                @if($parentModel->permissions()["M"])
                    <div class="forms_tr" style="text-align:left;">{{trans("rdp.add")}}</div>
                    {!!$newModel->openForm(['class'=>'forms_tr new_line'])!!}                        
                        {!!$newModel->input("id"); !!}{!!$newModel->input("parent"); !!}
                        <div class="forms_td">{!!$newModel->input("date");!!}</div>
                        <div class="forms_td">{!!$newModel->input("description",["style"=>"width:15em"]);!!}</div>
                        <div class="forms_td">{!!$newModel->input("hospitalization_days",["style"=>"width:4em"]);!!}</div>
                        <div class="forms_td">{!!$newModel->input("dialysis_related");!!}</div>
                        <div class="forms_td">
                            <button class="icon_button add" data-op="add" title="{{trans('rdp.add')}}" ></button>
                        </div>
                    {!!$newModel->closeForm()!!} 
                @endif
            </div>
        </div>
    @endif
@endsection