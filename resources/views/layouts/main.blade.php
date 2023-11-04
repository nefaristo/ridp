<!DOCTYPE html>
<html>
    <head>
        @include('includes.head')       
    </head>
    <body>
        <div id="model_container">
            @include('includes.header')              
            <div id="model_body">
                @yield('content')
            </div>
        </div>   
    </body>
</html>

