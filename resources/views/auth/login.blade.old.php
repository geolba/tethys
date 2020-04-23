@extends('settings.layouts.app')

@section('content')

<div class="pure-g  box-content">

    <div class="pure-u-1 pure-u-md-2-3">      

        <h1>Login</h1>

        <div class="panel-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form class="pure-form pure-form-stacked" role="form" method="POST" action="{{ url('/login') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="pure-control-group">
                    <label for="email">E-Mail Address</label>
                    <input type="email" class="pure-input-1" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
                    <span class="pure-form-message-inline">This is a required field.</span>
                </div>

                <div class="pure-control-group">
                    <label for="password">Password</label>
                    <input type="password" class="pure-input-1" name="password" id="password" placeholder="Password">
                </div>

                <div class="pure-controls">
                    <label for="remember" class="pure-checkbox">
                        <input name="remember" id="remember" type="checkbox"> Remember me
                    </label>
                    <button type="submit" class="pure-button pure-button-primary">Login</button>
                    {{-- <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a> --}}
                </div>
            </form>
        </div>
       
    </div>

</div>

@endsection
