<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"><!--almost obsolete? ctrl% of IE prior to 9 -->
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="icon" href="{!!asset('favicon.ico')!!}"/> 


<link rel="stylesheet" type="text/css" href="{{asset('css/normalize.css')}}">     
<link rel="stylesheet" type="text/css" href="{{asset('css/rdp.css')}}">
<link rel="stylesheet" type="text/css" href="{{URL::asset('js/jquery-ui-1.12.1.custom/jquery-ui.css')}}"></link>

<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script>var token='{{csrf_token()}}';</script>
<script src="{{URL::asset('js/jquery-sortable.js')}}"></script>       
<script src="{{URL::asset('js/moment-with-locales.js')}}"></script>  
<script src="{{URL::asset('js/SVLibs.js')}}?<?php echo time(); ?>"></script> 
<script src="{{URL::asset('js/rdp.js')}}?<?php echo time(); ?>"></script> 

@yield('otherHeads')

<title>~ Registro Italiano Dialisi Pediatrica ~ @yield('title')</title>