@extends('forms.common.form')
@section('form-data')
    @if ($model!=null)
        {!!$model->input("parent")!!}
        <table>
            <tr>
                <td>{!!$model->label("date");!!}</td>
                <td>{!!$model->input("date");!!}</td>
                <td>{!!$model->label("type");!!}</td>
                <td>{!!$model->input("type");!!}</td> 
                <td>{!!$model->label("replacement_frequency");!!}</td>
                <td>{!!$model->input("replacement_frequency",["style"=>"width:4em"]);!!}</td>                 
            </tr>             
        </table>        
    @endif
@endsection
