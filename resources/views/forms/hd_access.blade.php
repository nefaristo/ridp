@extends('forms.common.form')
@section('form-data')
    @if ($model!=null) 
        {!!$model->input("parent")!!}
        <table> 
            <tr>
                <td>{!!$model->label("date");!!}</td>                    
                <td>{!!$model->input("date");!!}</td>
                <td>{!!$model->label("access");!!}</td>                    
                <td>{!!$model->input("access");!!}</td>  
            </tr><tr>
                <td>{!!$model->label("description");!!}</td>                    
                <td>{!!$model->input("description",["style"=>"width:15em"]);!!}</td>                
            </tr>
        </table>
    @endif
@endsection