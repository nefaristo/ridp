@extends('forms.common.form')

@section('title', 'centri dialisi')

@section('content' )
******************************************************************************************************************
{!!$vs;!!}
 {!!Form::open(['route'=>'centers.store']);!!}
        {!! Form::label('code', 'Code:'); !!}
        {!! Form::text('code', $center->code, ['class' => 'form-control']); !!}
 {!!Form::button('button!');!!}<br>
 ID:{{$id}}

 {!!Form::close()!!}
@endsection