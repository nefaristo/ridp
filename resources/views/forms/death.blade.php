@extends('forms.common.subforms')
@section('form-data')
    @if($parentModel!=null)                 
        @foreach($models as $model)   
            {!!$model->openForm()!!}
            {!!$model->input("id");!!}   
            <div style="display:inline-block;width:90%;padding-left:40px;">
                <h4>{{trans("rdp.death_form")}}</h4>            
            </div>
            <div style="display:inline-block;">  
                <button class="icon_button save" data-op="save" title="{{trans('rdp.save')}}" ></button>
            </div>
            <table>
                <tr>
                    <td>{!!$model->label("date");!!}</td>
                    <td>{!!$model->input("date",["style"=>"width:10em;"]);!!}</td>
                    <td>{!!$model->label("cause");!!}</td>
                    <td>{!!$model->input("cause",["style"=>"width:100%"]);!!}</td>                                       
                </tr>
                <tr>
                    <td  colspan="2">{!!$model->label("cause_description");!!}</td>
                    <td colspan="4">{!!$model->input("cause_description",["style"=>"width:100%;"]);!!}</td>
                </tr>
                <tr>
                    <td colspan="2">{!!$model->label("renal_risk_factors");!!}</td>
                    <td colspan="4">{!!$model->input("renal_risk_factors",["style"=>"width:100%;"]);!!}</td>
                </tr>                    
                <tr>
                    <td colspan="2">{!!$model->label("other_risk_factors");!!}</td>
                    <td colspan="4">{!!$model->input("other_risk_factors",["style"=>"width:100%;"]);!!}</td>
                </tr>
                <tr>
                    <td colspan="2">{!!$model->label("subsequent_risk_factors");!!}</td>
                    <td colspan="4">{!!$model->input("subsequent_risk_factors",["style"=>"width:100%;"]);!!}</td>                    
                </tr>
                <tr>
                    <td colspan="2">{!!$model->label("autopsy");!!}</td>
                    <td colspan="4">{!!$model->input("autopsy",["style"=>"width:100%;height:5em;"]);!!}</td>
                </tr>                
            </table>               
            {!!$model->closeForm()!!}     
        @endforeach            
    @endif
@endsection