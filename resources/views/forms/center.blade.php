@extends('forms.common.form')
@section('form-data')
    @if ($model!=null) 
        <table>
            <tr>
                <td>{!!$model->label("code");!!}</td>
                <td  colspan="4">
                    @if(Auth::user()->privilege<10)
                        {!!$model->code;!!}
                    @else
                        {!!$model->input("code");!!}
                    @endif
                </td>
            </tr>
            <tr>
                <td>{!!$model->label("unit");!!}</td>
                <td>{!!$model->input("unit",["style"=>"width:17em"]);!!}</td>                 
                <td>{!!$model->label("institute");!!}</td>
                <td>{!!$model->input("institute",["style"=>"width:17em"]);!!}</td>                
            </tr> 
            <tr>
                <td>{!!$model->label("address");!!}</td>
                <td colspan="3">{!!$model->input("address",["style"=>"width:100%"]);!!}</td>                  
            </tr>
            <tr>
                <td>{!!$model->label("city");!!}</td>
                <td>{!!$model->input("city",["style"=>"width:17em"]);!!}</td>
                <td>{!!$model->label("province");!!}</td>                                 
                <td>{!!$model->input("province",["style"=>"width:100%"]);!!}</td>                                 
            </tr>
        </table>
    @endif
@endsection