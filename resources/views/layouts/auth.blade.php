<!DOCTYPE html>
<html>
    <head>
        @include('includes.head')       
    </head>
    <body>
        <div id="model_container"> 
            @include('includes.header_guest')            
            <div id="model_body" style="text-align: center">
                <h4>@yield('title')</h4>
                <div class="main_box">
                    @yield('content')             
                </div>
            </div>
        </div>      
    </body>
</html>

