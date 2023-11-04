@extends('forms.common.form')
@section('form-data')
    @if ($model!=null) 
        <table class="form-data">
            <tr>
                <td>{!!$model->label("username");!!}</td>
                <td>
                    @if(Auth::user()->privilege<10)
                        {!!$model->username;!!}
                    @else
                        {!!$model->input("username");!!}
                    @endif
                </td>              
                <td>{!!$model->label("email");!!}</td>
                <td>{!!$model->input("email",["style"=>"width:20em;"]);!!}</td>                               
            </tr>
            <tr>
                <td>{!!$model->label("center");!!}</td>
                <td  colspan="3">
                    @if($model->parentModel()!=NULL)
                        {!!$model->parentModel()->title();!!}
                    @endif
                </td> 
            </tr>
            <tr>
                <td>{!!$model->label("privilege");!!}</td>
                <td  colspan="3">
                    {!!$model->user()->UFPrivilege();!!}
                </td> 
            </tr>
            @if(Auth::user()->privilege>=10 && $model->privilege==1)
                <tr>                
                    <td>{!!$model->label("active");!!}</td>
                    <td>{!!$model->input("active");!!}</td> 
                </tr>
            @endif            
        </table>
    @endif
    {!!$model->errors()!!} 
@endsection