<!DOCTYPE html>
<html>
    <head>
        @include('includes.head')       
    </head>
    <body>
        <div id="model_container">
            @include('includes.small_header')              
            <div id="model_body" style="max-height:90vh;overflow-y:auto; margin: 2px auto;
    display:flex;flex-direction:column;flex-wrap:nowrap;justify-content: flex-start;">
                @yield('content')
            </div>
        </div>   
    </body>
</html>

