@extends('layouts.main_no_header')
@section('content')
    {!!$HTML["OPEN"]!!}
        <div class="quedi_section_container">
            <div class='quedi_section_header'>
                {!!trans("rdp.query_from")!!}
            </div>
            {!!$HTML["FROM"]!!}
        </div>
        <div class="quedi_section_container">
            <div  style="display:inline-block;width:50%;float:center;">
                <div class='quedi_section_header'>
                    {!!trans("rdp.query_select")!!}
                </div> 
                {!!$HTML["SELECT"]!!}
            </div> 
            <div style="display:inline-block;width:49%;float:center;">
                <div class='quedi_section_header'>
                    {!!trans("rdp.query_order")!!}
                </div>
                {!!$HTML["ORDER"]!!}
            </div> 
        </div>
        <div class="quedi_section_container">
            <div class='quedi_section_header'>
                {!!trans("rdp.query_where")!!}
            </div>
            {!!$HTML["WHERE"]!!}
        </div>  
        <div class="quedi_section_container"></div>   
    </div>
    {!!$HTML["CLOSE"]!!}      
@endsection