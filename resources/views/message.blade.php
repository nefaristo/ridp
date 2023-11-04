@extends('layouts.main')
@section('main_content_only')  
    <div>                
        @if(!empty($message))
            {!!$message!!}
        @endif        
    </div>    
@endsection
