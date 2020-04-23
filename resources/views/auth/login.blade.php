<!DOCTYPE html>
<html>

<head>
    <title>TETHYS - Geology Geophysics Meteorology</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Mobile Specific Metas
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- FONT
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" type="text/css" href="/css/fonts.css" />

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/skeleton/2.0.4/skeleton.min.css" /> -->
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/skeleton.css">
    <link rel="stylesheet" href="/css/font-awesome.css">
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: #f1f2f6;
            font-family: "Open Sans", sans-serif;
        }

        .logo-image {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .logo-image i {
            font-size: 30px;
        }

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
    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon/site.webmanifest">
    <link rel="mask-icon" href="/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/images/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-config" content="/images/favicon/browserconfig.xml">
    <meta name="theme-color" content="#646b63">
</head>

<body>
    <div class="logo-image">
        <a href="/">
            <i class="fas fa-chevron-circle-left"></i>
        </a>
    </div>
    <header>
        <h1>In Tethys einloggen</h1>
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
                            <input type="password" name="password" placeholder="Password*" class="u-full-width"
                                required>
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
</body>

</html>