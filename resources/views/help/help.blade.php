@extends('layouts.main_small_header')
@section('title',"help - " . $content["title"])
@section('content')
    <h2 style="text-align:center">{!!$content["title"]!!}</h2>
    @foreach($content["content"] as $k=>$v)
        <a name="{{$k}}"> 
            <h3 style="text-align:left">{!!$v["title"]!!}</h3>
        </a>
        <p id="{{$k}}" style="margin-bottom: 20px;">
            {!!str_replace("\n","<br>",$v["content"])!!}
        </p>
    @endforeach
@endsection