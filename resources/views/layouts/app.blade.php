<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" content="de">

    <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">
    <title>TETHYS - Geology Geophysics Meteorology</title>
    <meta name="description" content="An awesome one page website">
    <meta name="author" content="Arno Kaimbacher">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Specific Metas
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- FONT
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    {{-- <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" /> --}}
    <link rel="stylesheet" type="text/css" href="/css/fonts.css" />

    <!-- CSS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/skeleton.css">
    <link rel="stylesheet" href="/css/font-awesome.css">
    <link rel="stylesheet" href="/css/style.css">

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



    <!-- Javascript
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <script type="text/javascript" src="/js/scripts.js"></script>
    @yield('head')
</head>

<body class="layout-home-html">
    <div id="trynewsite">
        <span>TETHYS Testphase</span>
    </div>

    <!-- Menu -->
    <header class="header">
        <nav class="navigation" id="nav">
            <a href="#" class="menu-icon {{ Route::currentRouteName() != 'frontend.home.index' ? 'active' : '' }}"">
                <i class=" fa fa-bars"></i>
            </a>
            <a href="https://www.geologie.ac.at/" target="_blank" class="logo">
                <img src="/images/gba_logo.png" alt="Logo white" width="60" height="30">               
            </a>
            <div class="container">
                <ul class="menu">
                    <!-- <li><a href="#hero">Home</a></li> -->
                    <li>
                        <a class="{{ Route::currentRouteName() == 'frontend.home.index' ? 'current' : '' }}"
                            href="{{ url('/') }}">Home</a>
                    </li>
                    <!-- <li><a href="#introduction">Introduction</a></li> -->
                    <li>
                        <a class="{{ Route::currentRouteName() == 'frontend.home.intro' ? 'current' : '' }}"
                            href="{{ route('frontend.home.intro') }}">Intro</a>
                    </li>
                    <li>
                        <a class="{{ Route::currentRouteName() == 'frontend.search.index' ? 'current' : '' }}"
                            href="{{ route('frontend.search.index') }}">Search</a>
                    </li>

                    <!-- <li><a href="#work">Work</a></li> -->
                    <li>
                        <a class="{{ Route::currentRouteName() == 'frontend.home.services' ? 'current' : '' }}"
                            href="{{ route('frontend.home.services') }}">Services</a>
                    </li>
                    <!-- <li><a href="#help">Help</a></li> -->
                    <li>
                        <a class="{{ Route::currentRouteName() == 'frontend.home.help' ? 'current' : '' }}"
                            href="{{ route('frontend.home.help') }}">Help</a>
                    </li>
                    <!-- <li><a href="#clients">Clients</a></li> -->
                    <!-- <li><a href="#about">About</a></li> -->
                    <!-- <li>
                        <a class="{{ Route::currentRouteName() == 'frontend.pages.show' ? 'current' : '' }}"
                            href="{!! url('/pages/about') !!}">About</a>
                    </li> -->
                    <li class="right"><a href="{{ URL::route('oai') }}" target="_blank"> OAI</a></li>

                    <!-- <li><a href="#why-us">Why us?</a></li>            
                    <li><a href="#contact">Contact</a></li> -->
                    <!-- <li class="right"><a href="#">Login</a></li> -->
                    @if (Auth::guest())
                    <li class="right">
                        <a class="{{ Route::currentRouteName() == 'login' ? 'current' : '' }}"
                            href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    @else

                    <li class="right">
                        <a class="pure-menu-link" href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt"></i> LOG OUT
                        </a>
                    </li>

                    @endif
                </ul>
            </div>
        </nav>
    </header>

    @yield('hero')

    <div class="content-container">
        @yield('content')
    </div>


    <!-- Footer -->
    <section data-sr class="footer u-full-width u-max-full-width">
        <div class="container">
            <div class="row">
                <div class="four columns footer-about">
                    <!-- <h5>&copy 2015 Tuts+ Web Design.</h5> -->
                    <div class="block">
                        <h3 class="block-title">About TETHYS</h3>
                        <ul>
                            <li class="first"><a href="{{ URL::route('oai') }}" target="_blank"> OAI</a></li>
                            <li class="last">
                                <a href="https://www.geologie.ac.at/" target="_blank">About GBA</a>
                            </li>
                            <li><a href="{{ URL::route('frontend.home.news') }}">News</a></li>
                        </ul>
                    </div>
                </div>
                <div class="four columns footer-links">
                    <div class="block">
                        <h3 class="block-title">TOOLS &amp; SUPPORT</h3>
                        <ul id="secondary-nav" class="nav">
                            <li class="first"><a href="{{ URL::route('frontend.home.contact') }}">Contact</a></li>
                            <li class="last"><a
                                    href="{!! URL::route('frontend.pages.show', ['page_slug'=>'imprint']) !!}">Impressum</a>
                            </li>
                            <li class="last"><a href="{{ URL::route('frontend.sitelinks.index') }}">Sitelinks</a></li>
                            <li class="last"><a
                                    href="{!! URL::route('frontend.pages.show', ['page_slug'=>'terms-and-conditions']) !!}">Terms
                                    and Conditions</a></li>

                            <li><a target="_blank" href="https://github.com/geolba"><i class="fab fa-github"></i> rdr
                                    bei GitHub</a></li>
                        </ul>
                    </div>
                </div>
                <div class="four columns block">
                    <div class="block">
                        <h3 class="block-title">CONNECT WITH US</h3>
                        <ul>
                            <li><a target="_blank" href="https://www.geologie.ac.at/"><i class="fas fa-home"></i>
                                    GBA</a></li>
                            <li><span><i class="fas fa-phone"></i> +43-1-7125674</span></li>
                            <li><a href="mailto:repository@geologie.ac.at?Subject= RDR &amp;body=How can I help you?"><i
                                        class="far fa-envelope"></i> repository@geologie.ac.at</a> </li>
                            <li></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    @yield('after-scripts')
</body>

</html>