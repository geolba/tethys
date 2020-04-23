@extends('layouts.app')

@section('head')
<style type="text/css">
    h1 {
        text-align: center;
        margin: 0;
        padding-top: 20px;
    }

    .main p {
        text-align: center;
    }

    .main {
        /* display: flex; */
        margin: 0 auto;
        width: 60%;
        justify-content: center;
        align-items: center;
    }

    input[type=submit].button-primary {
        font-size: 14px;
    }

    form {
        /* width: 40%; */
        margin-top: 30px;

    }

    @media(max-width: 660px) {

        /* form {
                width: 70%;
            } */
        .main {
            width: 80%;
        }
    }
</style>
@endsection

@section('content')
<section data-sr id="login" class="login u-full-width"></section>
    <header>
        <h1>Tethys Login Form</h1>
    </header>
    <div class="main">
        <p>Login is only required for access to data under embargo or for submitting new datasets.</p>

        @if (count($errors) > 0)
        <div class="row">
            <div class="twelve columns alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="twelve columns">
                <form class="pure-form pure-form-stacked" role="form" method="POST" action="{{ url('/login') }}">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="row">
                        <div class="twelve columns">
                            <input type="email" name="email" placeholder="Email*" class="u-full-width"
                                value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="twelve columns">
                            <input type="password" name="password" placeholder="Password*" class="u-full-width" required>
                        </div>
                    </div>


                    <label for="remember" class="example-send-yourself-copy">
                        <input type="checkbox" name="remember">
                        <span class="label-body">Remember me</span>
                    </label>

                    <div class="row">
                        <div class="twelve">
                            <input type="submit" name="" class="button-primary u-full-width" value="Login">
                        </div>
                    </div>
                    <!-- <h3>Einen neuen Benutzer registrieren</h3>  -->
                    <!-- <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a> -->
                </form>
            </div>
        </div>



    </div>
</section>
@endsection