@extends('layouts.main_content_only')
@if(isset($query))
    {!!$query["name"]!!}    
    @if($query['description'])
        ({!!$query['description']!!})
    @endif   
    @if($query['notes'])
        ({!!$query['notes']!!})
    @endif       
    <br>
    @if($query["uf_parameters"])
        {!!$query["uf_parameters"]!!}
    @endif
    (@lang("rdp.updated_to") {!!$query["datetime"]!!})
    @lang("rdp.results"): {!!($query["n"])!!} 
@endif