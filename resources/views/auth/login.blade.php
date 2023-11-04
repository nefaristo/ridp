@extends('layouts.auth')
@section('title',trans('rdp.login'))
@section('content')
    <form role="form" method="POST" action="{{ url('/login') }}" >
        {{ csrf_field() }}
        <table>
            <tr>
                <td>
                    <label for="username" class="col-md-4 control-label">@lang("rdp.username")</label>
                </td>
                <td>
                    <input id="username" type="username" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <label for="password" class="col-md-4 control-label">@lang("rdp.password")</label>
                </td>
                <td>
                    <input id="password" type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center">
                    <label>
                        <input type="checkbox" name="remember"> @lang("rdp.remember me")
                    </label>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center">
                    <button type="submit" class="btn btn-primary">
                        @lang("rdp.login")
                    </button>                   
                </td>
            </tr>
            <tr>
                <td colspan="2" style="display:none;text-align: center">
                    <a href="{{ url('/password/reset') }}">
                        @lang("rdp.forgot your password")
                    </a> 
                </td>
            </tr>
            <tr>
                <td colspan="2">                    
                    @foreach($errors->all() as $error)
                        <span class="help-block">
                            <strong>{{$error}}</strong>
                        </span>
                    @endforeach
                </td>
            </tr>
        </table>
    </form>         
@endsection
