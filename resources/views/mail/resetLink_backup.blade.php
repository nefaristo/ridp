<!DOCTYPE html>
<html>
    <head>
        @include('includes.head') 
        <style> 
            a{color: #336699;cursor:pointer;}
        </style>
    </head>
    <body>        
        <div id="model_container">                        
            <div id="model_body">
                @yield('content')
            </div>
        </div>   
    </body>
</html>
