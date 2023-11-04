@extends('layouts.auth')

@section('title',trans("rdp.setting_password"))
@section('content') 
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <input id="email" type="hidden" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
        <table>
            <tr>
                <td><label for="username" class="col-md-4 control-label">Username</label></td>
                <td><input id="username" type="text" readonly class="form-control" name="username" value="{{ $user->username or old('username') }}"></td>
            </tr>            
            <tr>
                <td>
                    <label for="password" class="col-md-4 control-label">Password</label>
                </td>
                <td>
                    <input id="password" type="password" class="form-control" name="password" required>
                    @if ($errors->has('password'))
                        <br>
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                </td>
                <td>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    @if ($errors->has('password_confirmation'))
                        <br>
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center">
                    <button type="submit" >{{trans("rdp.set_password")}}</button>
                </td>
            </tr>
        </table>
    </form>
@endsection