@extends('layouts.main')
@section('otherHeads')
    <script src="{{URL::asset('js/rdp_hash.js')}}?<?php echo time(); ?>"></script> 
@endsection
@section('title',$title)
@section('content')  
    <div>                
        @if(!empty($content))
            {!!$content!!}
        @endif
    </div>    
@endsection
