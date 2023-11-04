<div id="model_header">
    <div style="display:flex;flex-direction:row;flex-wrap:nowrap;
    justify-content:space-around;align-items: center; align-content:space-around;">
        <div style="font-size:70%">
            @lang("rdp.head-version")<br>
            @if(\Auth::user())
                @lang("rdp.user") : {{\Auth::user()['username']}}
                <!--@lang("rdp.session") : {{session("id","-")}}-->
            @endif 
        </div>
        <div style="font-size: 110%;font-weight: bold;">
            Registro Italiano Dialisi Pediatrica
        </div>
        <div style="font-size:70%">
            @lang("rdp.head-sponsor") 
        </div>
    </div>
    <div style="display:flex;flex-direction:row;flex-wrap:nowrap;
    justify-content:space-around;align-items: center; align-content:space-around;font-size:100%;
        margin-top:0.5vh;padding-top:0.5vh;margin-bottom:0.5vh;
    border-top: 1px solid rgb(190,220,235);">
        
        <div>{!!App\Libs\utils::link(route('home'),trans('rdp.home'))!!}</div>
        <div>{!!App\Libs\utils::link(route('patients'),trans('rdp.patients'))!!}</div>
        <div>{!!App\Libs\utils::link(url('/queries'),trans('rdp.queries'))!!}</div>        
        @if(Auth::user()->privilege>=10)
            <div>{!!App\Libs\utils::link(route('centers'),trans('rdp.centers'))!!}</div>
            <div>{!!App\Libs\utils::link(route('users'),trans('rdp.users'))!!}</div>
            <div>{!!App\Libs\utils::link(route('dataCheck'),trans('rdp.data_check'))!!}</div>
        @else
            <div>{!!App\Libs\utils::link(url('/contacts'),trans('rdp.contacts'))!!}</div> 
        @endif          
        <div>
            <a href="#" onclick="$('#language_menu').toggle();return false;">
                {{ Config::get('languages')[App::getLocale()] }}
            </a>
            <div id="language_menu" style="position:absolute;z-index:10;display: none">
                <ul>
                    @foreach (Config::get('languages') as $lang => $language)
                        <li>
                            @if ($lang != App::getLocale())
                                <a href='{{url("user/lang/{$lang}")}}'>
                                    {{$language}}
                                </a>
                        @else
                            {{$language}}                       
                        @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div>
            @if(\Auth::user())
                <a href="{{ url('/logout') }}" 
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                     @lang("rdp.logout")
                </a>                
                <form id="logout-form" 
                    action="{{ url('/logout') }}" method="POST" 
                    style="display: none;">
                    {{ csrf_field() }}
                </form>
            @else
                <a href="{{route('login')}}" >@lang("rdp.login")</a>
            @endif     
        </div>
    </div>            
</div>
