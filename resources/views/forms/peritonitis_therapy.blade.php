@extends('forms.common.subforms')
@section('form-data')
    @if($parentModel!=null)  
        <div class="forms_table">
            <div class="forms_thead">
                <div class="forms_tr">
                    <div class="forms_td">{!!$newModel->label("start_date",trans("rdp.period"));!!}</div>
                    <div class="forms_td">{!!$newModel->label("drug");!!}</div>
                    <div class="forms_td">{!!$newModel->label("dose");!!}</div>
                    <div class="forms_td">{!!$newModel->label("intermittent","interm.");!!}</div>
                    <div class="forms_td">{!!$newModel->label("loading_dose_administration",trans("rdp.loading_dose"));!!}</div>
                    <div class="forms_td"></div>
                </div>
            </div>    
            <div class="forms_tbody">
                @foreach($models as $model)
                    {!!$model->openForm(["class"=>"forms_tr"])!!}
                        {!!$model->input("id"); !!}
                        <div class="forms_td">
                            {!!$model->input("start_date",[],["placeholder"=>"?"]);!!} - {!!$model->input("end_date",[],["placeholder"=>"?"]);!!}
                        </div>
                        <div class="forms_td">
                            {!!$model->input("drug",[],["placeholder"=>"-"]);!!}
                            &nbsp;{{trans("rdp.via")}}&nbsp;
                            {!!$model->input("administration_via",[],["placeholder"=>"-"]);!!}
                        </div>
                        <div class="forms_td">                            
                            {!!$model->input("dose",[],["placeholder"=>"-"]);!!}&nbsp; 
                            <span class="therapy_unit">{!!$model->unit()!!}</span>
                        </div>
                        <div class="forms_td">
                            {!!$model->input("intermittent",[],["placeholder"=>"-"]);!!}
                        </div>
                        <div class="forms_td">
                            {!!$model->input("loading_dose_administration",[],["placeholder"=>"-"]);!!}
                        </div>
                        <div class="forms_td" style="display:flex">
                            <div>
                                <button class="icon_button save" data-op="save" title="{{trans('rdp.save')}}" ></button>
                            </div><div>  
                                <button class="icon_button delete" data-op="delete" title="{{trans('rdp.delete')}}" ></button>
                            </div>
                        </div> 
                        <hr>                                           
                    {!!$model->closeForm()!!}                         
                @endforeach           
                @if($newModel->permissions()["M"]) 
                    {!!$newModel->openForm(["class"=>"forms_tr new_line"])!!}                    
                        {!!$newModel->input("id");!!}{!!$newModel->input("parent");!!}
                        <div class="forms_td" style="width:14em">
                            {!!$newModel->input("start_date",["style"=>"width:10em;"]);!!}
                            <br>-<br>
                            {!!$newModel->input("end_date",["style"=>"width:10em;"]);!!}
                        </div>
                        <div class="forms_td">
                            {!!$newModel->input("drug");!!}
                            <br>
                            {!!$newModel->label("administration_via",trans("rdp.via"));!!}
                            <br>
                            {!!$newModel->input("administration_via");!!}                            
                        </div>
                        <div class="forms_td">
                            {!!$newModel->input("dose",["style"=>"width:4em"]);!!} &nbsp;                                                       
                            <br><span class="therapy_unit">mg/kg/die</span>
                        </div>
                        <div class="forms_td">
                            {!!$newModel->input("intermittent");!!} 
                        </div>
                        <div class="forms_td">
                            {!!$newModel->input("loading_dose_administration");!!} 
                        </div>                        
                        <div class="forms_td">
                            <button class="icon_button add" data-op="add" title="{{trans('rdp.add')}}" ></button>
                        </div>
                    {!!$newModel->closeForm()!!} 
                @endif
            </div>
        </div>
    <script>
        $(function(){
            $('#subform_container').on('change.administration_via','[name="administration_via"]',function(){
                $(this).closest("form").find(".therapy_unit").html((($(this).val()==2)?'mg/l':'mg/kg/die'));
            })            
        })
    </script>
    @endif
@endsection