<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel='stylesheet' href="{{ asset('css/pure-min.css') }}" />
    <link rel='stylesheet' href="{{ asset('css/grids-responsive-min.css') }}" />   
   <!-- Styles -->
    <link rel='stylesheet' href="{{ asset('css/font-awesome.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('/backend/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/pagination.css') }}">

        
    
    </head>
    <body>
        <div id="app">
            <div class="container">
                <my-vuetable></my-vuetable>
            </div>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>