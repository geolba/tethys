<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">    
    <meta http-equiv="Content-Language" content="de">
   
    <!--<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">-->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') RDR</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}"> --}}

    <link rel='stylesheet' href="{{ asset('css/pure-min.css') }}" />
    <link rel='stylesheet' href="{{ asset('css/grids-responsive-min.css') }}" />
    <!--<link href="{{ asset('css/app1.css') }}" rel="stylesheet" />-->
    <!--<link rel='stylesheet' href="{{ asset('css/page.css') }}" />--> 
    <link rel='stylesheet' href="{{ asset('css/styles.css') }}" />
    <link rel='stylesheet' href="{{ asset('css/langswitch.css') }}" />
    <!-- Fonts -->
    <link rel='stylesheet' href="{{ asset('css/font-awesome.css') }}" />
    <link href="http://fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C700italic%2C400%2C300%2C700%2C800&amp;ver=3.8.1" type="text/css" rel="stylesheet">

    @yield('head')


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="layout-home-html">

    @include('partials.nav')
    <div id="mobile-menu" class="pure-hidden-phone pure-hidden-tablet pure-hidden-desktop">
        <ul></ul>
    </div>
    @yield('slider')

    <div class="content-container">
        <div class="container">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>

    <!--@yield('footer')-->
    <div class="footer">
        <div class="container">

            <div class="pure-g">
                <div class="pure-u-1 pure-u-md-1-4 footer-about">
                    <div class="block">
                        <h3 class="block-title">About RDR</h3>
                        <ul>
                            {{-- <li><a href="{{ URL::route('frontend.home.about') }}">About Us</a></li> --}}
                            <li class="last"><a href="{!! URL::route('frontend.pages.show', ['page_slug'=>'about']) !!}">About Us</a></li>
                            <li><a href="{{ URL::route('frontend.home.news') }}">News</a></li>
                        </ul>
                    </div>
                </div>
                <div class="pure-u-1 pure-u-md-1-4">
                </div>
                <div class="pure-u-1 pure-u-md-1-4 footer-links">
                    <div class="block">
                        <h3 class="block-title">TOOLS &amp; SUPPORT</h3>
                        <ul id="secondary-nav" class="nav">                            
                            <li class="first"><a href="{{ URL::route('frontend.home.contact') }}">Contact</a></li>
                            {{-- <li><a href="{{ URL::route('frontend.home.imprint') }}">Impressum</a></li> --}}
                            <li class="last"><a href="{!! URL::route('frontend.pages.show', ['page_slug'=>'imprint']) !!}">Impressum</a></li>
                            <li class="last"><a href="{{ URL::route('frontend.sitelinks.index') }}">Sitelinks</a></li>
                            <li class="last"><a href="{!! URL::route('frontend.pages.show', ['page_slug'=>'terms-and-conditions']) !!}">Terms and Conditions</a></li>

                            <li><a target="_blank" href="https://github.com/geolba"><i class="fa fa-github"></i> rdr bei GitHub</a></li>
                        </ul>
                    </div>
                </div>
                <div class="pure-u-1 pure-u-md-1-4 block">
                    <div class="block">
                        <h3 class="block-title">CONNECT WITH US</h3>
                        <ul>
                            <li><a target="_blank" href="https://www.geologie.ac.at/"><i class="fa fa-home"></i> GBA</a></li>
                            <li><span><i class="fa fa-mobile-phone"></i> +43-1-7125674</span></li>
                            <li><a href="mailto:repository@geologie.ac.at?Subject= RDR &amp;body=How can I help you?"><i class="fa fa-envelope-o"></i> repository@geologie.ac.at</a> </li>
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr>
            <div class="pure-g">
                <div class="pure-u-1 pure-u-md-1-4 footer-copyright">
                    Geologische Bundesanstalt &copy; {{ date('Y') }}
                    <!--<p id="logo-wrapper">
                        <a href="http://www.kobv.de/opus4/" title="Opus4 Website">
                        </a>
                    </p>-->
                </div>
                <div class="pure-u-1 pure-u-md-1-4 footer-funded">
                {{-- @role('administrator')
                    I'm an administrator!
                @else
                    I'm not an administrator...
                @endrole --}}
                </div>
                <div class="pure-u-1 pure-u-md-1-4 footer-funded">
                </div>
                <div class="pure-u-1 pure-u-md-1-4 footer-funded">  
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    {{-- <script type="text/javascript" src="{{ asset('js/jquery-2.1.1.min.js') }}"></script> --}} 
    <script type="text/javascript" src="{{ asset('js/lib.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('div.alert').not('alert-important').delay(3000).slideUp(300);
        });

    </script>
    @yield('scripts')

</body>
</html>