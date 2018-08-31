<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin PureRDR</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel='stylesheet' href="{{ asset('css/pure-min.css') }}" />
    <link rel='stylesheet' href="{{ asset('css/grids-responsive-min.css') }}" />
    
    <!-- <script src="bower_components/chart.js/dist/Chart.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script>
    <!--<link rel="stylesheet" type="text/css" href="bower_components/font-awesome/css/font-awesome.min.css">-->
    <link rel='stylesheet' href="{{ asset('css/font-awesome.css') }}" />
    

    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/pagination.css') }}">
</head>
<body>

<div id="layout">

    <a href="#menu" id="menuLink" class="menu-link">
        <!-- Hamburger icon -->
        <span></span>
    </a>

    <div id="menu">
        <nav class="pure-menu">
            <h1 class="site-logo">Admin<strong>Rdr</strong></h1>
            <div class="menu-item-divided"></div>
            
            <h2 class="pure-menu-heading">Home</h2>
            <ul class="pure-menu-list">
                <li class="pure-menu-item {{ Route::is('settings.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('settings.dashboard') }}" class="pure-menu-link">Reports</a>
                </li>				
            </ul>

            @permission('settings')
            <h2 class="pure-menu-heading">Settings</h2>
            <ul class="pure-menu-list">				
                <li class="pure-menu-item {{ Route::is('settings.document*') ? 'active' : '' }}">
                    <a class="pure-menu-link" href="{{ route('settings.document') }}"><i class="fa fa-database"></i> Datasets</a>
                </li>
                <li class="pure-menu-item {{ Route::is('settings.collection*') ? 'active' : '' }}">
                    <a class="pure-menu-link" href="{{ route('settings.collection') }}"><i class="fa fa-archive"></i> Collections</a>
                </li>
                <li class="pure-menu-item {{ Route::is('settings.license*') ? 'active' : '' }}">
                    <a href="{{ route('settings.license') }}" class="pure-menu-link"><i class="fa fa-file"></i> Licenses</a>
                </li>
                <li class="pure-menu-item {{ Route::is('settings.person*') ? 'active' : '' }}">
                    <a href="{{ route('settings.person') }}" class="pure-menu-link"><i class="fa fa-edit"></i> Persons</a>
                </li>
                <li class="pure-menu-item {{ Route::is('settings.project*') ? 'active' : '' }}">
                    <a class="pure-menu-link" href="{{ route('settings.project') }}"><i class="fa fa-tasks"></i> Projects</a>
                </li>
                
            </ul>
            @endpermission

            @permission('review')
            <h2 class="pure-menu-heading">Publish</h2>
            <ul class="pure-menu-list">		
                <li class="pure-menu-item {{ Route::is('publish.dataset.*') ? 'active' : '' }}">
                    <a class="pure-menu-link" href="{{ URL::route('publish.dataset.create') }}"><i class="fa fa-upload"></i> Publish</a>
                </li>		
            </ul>
            @endpermission

            <h2 class="pure-menu-heading">Access Management</h2>
            <ul class="pure-menu-list">
                @if (Auth::guest())
                <li class="pure-menu-item {{ Route::currentRouteName() == 'login' ? 'active' : '' }}">
                    <a class="pure-menu-link" href="{{ route('login') }}">LOGIN</a>
                </li>
                @else
                @permission('settings')
                <li class="pure-menu-item {{ Route::is('settings.user.*') ? 'active' : '' }}">
                        <a class="pure-menu-link" href="{{route('settings.user.index') }}"><i class="fa fa-users"></i> User Management</a>
                    </li>
                    <li class="pure-menu-item {{ Route::is('role.*') ? 'active' : '' }}">
                        <a class="pure-menu-link" href="{{route('role.index') }}"><i class="fa fa-key"></i> Role Management</a>
                    </li>
                <li class="pure-menu-item">
                    <a class="pure-menu-link" href="{{ route('settings.user.edit',['id'=>Auth::user()->id]) }}"><i class="fa fa-user"></i> EDIT</a> 
                </li>	
                @endpermission			
                <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('logout') }}"><i class="fa fa-sign-out"></i>Logout</a></li>
                @endif
            </ul>
        </nav>
    </div>

    <div id="main">

        <div class="header">
            <div class="pure-g">
                <div class="pure-u-1-2"><h1>Dashboard</h1></div>
                <div class="pure-u-1-2 text-right">					
                    <section class="user-info">
                        @if(Auth::user())
                        <i class="fa fa-user"></i> <a href="#" rel="User">{{ Auth::user()->login }}</a>					
                        <span class="divider"></span>
                        @endif
                        <i class="fa fa-cog"></i> <a href="#" rel="User">Settings</a>
                    </section>					
                </div>
            </div>				
        </div>

        <div class="content">
            {{-- <div class="breadcrumb">
                <i class="fa fa-home"></i><a href="#" rel="Dashboard">Dashboard</a>
                <i class="fa fa-angle-right"></i><a href="#" rel="Dashboard">Sales</a>
            </div> --}}
            {{-- @yield('breadcrumbs') --}}
            @if(Breadcrumbs::exists())
                {!! Breadcrumbs::render() !!}
            @endif
            {{-- <div class="pure-g"> --}}
            <div class="box">
                <div class="l-box">		
                            @include('partials.flash')
                            @yield('content')						
                </div>
            </div>
                {{-- <div class="pure-u-1-2 box">
                    <div class="l-box">
                        <div class="header">
                            <h3 class="header-title">Message</h3>
                        </div>
                        <div class="box-content">
                            
                            <form class="pure-form pure-form-stacked">
                                <div class="pure-g">
                                    <div class="pure-u-1-1">
                                        <label for="title">Title</label>
                                        <input id="title" type="text" class="pure-u-1-1">

                                        <label for="post">Post Content</label>
                                        <textarea id="post" rows="10" class="pure-u-1-1"></textarea>

                                        <hr>

                                        <button class="pure-button pure-button-primary">Save</button>
                                        <button class="pure-button">Save in Draft</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> --}}
            {{-- </div> --}}

            {{-- <div class="pure-g">
                <div class="pure-u-1-2 box">
                    <div class="l-box">
                        <div class="header">
                            <h3 class="header-title">Messages</h3>
                        </div>
                        <div class="box-content">
                            <span class="msg success"><i class="fa fa-check"></i>Message sending success!</span>
                            <span class="msg error"><i class="fa fa-ban"></i>Message NOT sending verify errors!</span>
                            <span class="msg alert"><i class="fa fa-exclamation-triangle"></i>Your permit geolocalization?</span>
                        </div>
                    </div>
                </div>
                <div class="pure-u-1-2 box">
                    <div class="l-box">
                        <div class="header">
                            <h3 class="header-title">Lists Content</h3>
                        </div>
                        <div class="box-content">
                            <p>adfas</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="pure-g">
                <div class="pure-u-4-5">
                    <div class="l-box">
                        <div class="header">
                            <h3 class="header-title">Edit Item</h3>
                        </div>
                        <div class="box-content">
                            <p>Content</p>
                        </div>
                    </div>
                </div>
                <div class="pure-u-1-5">
                    <div class="l-box">
                        <div class="header">
                            <h3 class="header-title">Sidebar</h3>
                        </div>
                        <div class="box-content">
                            <p>Sidebar content</p>
                        </div>
                    </div>
                    
                </div>
            </div> --}}

        </div>
    </div>


<script type="text/javascript" src="{{ asset('js/lib.js') }}"></script>
<script type="text/javascript">
    $('div.alert').not('alert-important');//.delay(3000).slideUp(300);
</script>
<script type="text/javascript" src="{{ asset('assets/functions.js') }}"></script>
@yield('scripts')
</div>

</body>
</html>