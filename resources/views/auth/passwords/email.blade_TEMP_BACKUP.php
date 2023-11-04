@extends('layouts.main')

@section('title','reset password')
@section('content')
{{session("status")}}
    <div style="text-align: center; margin-top:20px;">
        <div class="main_box">
            @if (session('status'))
                <div style="text-align:center">
                    {{ session('status') }}<br>
                    {!!link_to_route("home","home")!!}
                </div> 
            @else
                <form  method="POST" action="{{ url('/password/email') }}"> 
                    {{ csrf_field() }}
                    <div style="text-align: center">
                    <!--<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>-->
                    @lang("rdp.send_reset_to_mail")<br>
                    @if(Auth::user()->privilege>=10)
                    {!!Form::select("email",DBUtils::DBoutputList("SELECT email, CONCAT(username,' (',email,')') AS description FROM users WHERE privilege<=" .  \Auth::user()->privilege . "  AND active=true AND email IS NOT NULL ORDER by privilege desc, username"),["id"=>"email"]);!!}<br>
                    @else
                        <input id="email" type="email" style="width:20em;display:none"  name="email" value="{{\Auth::user()->email}}" required readonly>
                        {{\Auth::user()->email}}
                    @endif   
                    <br>
                    <input type="submit" value="{{trans("rdp.send")}}" style="width:20em;margin: 3px">
                    <br>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    </div>
                </form>
            @endif          
        </div>
    </div>          
@endsection
