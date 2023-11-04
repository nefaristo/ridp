@extends('forms.common.subform')
@section('form-data')
    @if ($model!=null) 
        <?php $fill=["style"=>"width:100%;"]; $style=["style"=>"width:50px;"];?>
        {!!$model->input("parent")!!}
        <div>
            {!! trans("rdp.hd_prescriptions") ." - ". $model->htmlFormCompletion()!!}
        </div>    
        <table>
            <tr>
                <td>{!!$model->label("sessions_per_week");!!}</td>
                <td>{!!$model->input("sessions_per_week",$style)!!}</td>
                <td>{!!$model->label("session_duration");!!}</td>
                <td>{!!$model->input("session_duration",$style)!!}</td>
                <td>{!!$model->label("membrane_type");!!}</td>
                <td>{!!$model->input("membrane_type");!!}</td>
            </tr>                
            <tr>
                <td>{!!$model->label("filter_surface");!!}</td>                
                <td>{!!$model->input("filter_surface",$style)!!}</td>
                <td>{!!$model->label("blood_flow");!!}</td>                
                <td>{!!$model->input("blood_flow",$style)!!}</td>
                <td>{!!$model->label("modality");!!}</td>                
                <td>{!!$model->input("modality",$fill);!!}</td>
            </tr>
        </table>
    @endif
@endsection