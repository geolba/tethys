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
    <!--<link rel="stylesheet" type="text/css" href="bower_components/font-awesome/css/font-awesome.min.css">-->
    <link rel='stylesheet' href="{{ asset('css/font-awesome.css') }}" />
    

    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/pagination.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/tabs.css') }}">
    @yield('styles')
</head>
<body>

<div id="layout">

    <a href="#menu" id="menuLink" class="menu-link">
        <!-- Hamburger icon -->
        <span></span>
    </a>

    <div id="menu">
        <nav class="pure-menu sidebar-menu">
            <h1 class="site-logo">Admin<strong>Rdr</strong></h1>
            <div class="menu-item-divided"></div>
            
            {{-- <h2 class="pure-menu-heading">Home</h2> --}}
            <ul class=" pure-menu-list sidebar-menu">
                <h2 class="pure-menu-heading">Home</h2> 

                {{-- <li class="pure-menu-item {{ Route::is('settings.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('settings.dashboard') }}" class="pure-menu-link">Reports</a>
                </li>	 --}}
               
                
                <li class="{{ active_class(Route::is('settings.dashboard')) }}">
                    <a href="{{ route('settings.dashboard') }}">
                        <i class="fa fa-dashboard"></i>
                        <span>Reports</span>
                    </a>
                </li>

                @permission('settings')
                <li class="treeview">
                    <h2 class="pure-menu-heading">Settings  <span class="fa fa-angle-down"></h2>
                    <ul class="pure-menu-list treeview-menu {{ active_class(Route::is('settings.*'), 'menu-open') }}" style="display: none; {{ active_class(Route::is('settings.*'), 'display: block;') }}">				
                        <li class="pure-menu-item {{ Route::is('settings.document*') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{ route('settings.document') }}"><i class="fa fa-database"></i> Datasets</a>
                        </li>
                        {{-- <li class="pure-menu-item {{ Route::is('settings.collection*') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{ route('settings.collection') }}"><i class="fa fa-archive"></i> Collections</a>
                        </li> --}}
                        <li class="pure-menu-item {{ Route::is('settings.collectionrole') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{ route('settings.collectionrole.index') }}"><i class="fa fa-archive"></i> Collection Roles</a>
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

                        <li class="pure-menu-item {{ Route::is('settings.mimetype') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{ route('settings.mimetype.index') }}"><i class="fa fa-archive"></i> Mimetypes</a>
                        </li>

                        @permission('page')
                        <li class="{{ active_class(Active::checkUriPattern('settings/page*')) }}">
                            <a class="pure-menu-link" href="{{ route('settings.page.index') }}">
                                <i class="fa fa-file-text"></i>
                                <span>{{ trans('labels.backend.pages.title') }}</span>
                            </a>
                        </li>
                        @endpermission
                        
                    </ul>
                </li>
                @endpermission

                @role(array('administrator', 'editor', 'reviewer'))
                <li class="treeview">
                    <h2 class="pure-menu-heading">Publish</h2>
                    <ul class="pure-menu-list">		
                        <li class="pure-menu-item {{ Route::is('publish.dataset.create') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{ URL::route('publish.dataset.create') }}"><i class="fa fa-upload"></i> Create</a>
                        </li>
                        <li class="pure-menu-item {{ Route::is('publish.workflow.index') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{ URL::route('publish.workflow.index') }}"><i class="fa fa-upload"></i> All my datasets</a>
                        </li>	
                        <li class="pure-menu-item {{ Route::is('publish.workflow.editorIndex') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{ URL::route('publish.workflow.editorIndex') }}"><i class="fa fa-upload"></i> EDITOR PAGE: Released datasets</a>
                        </li>	
                        {{-- <li class="pure-menu-item {{ Route::is('publish.workflow.release') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{ URL::route('publish.workflow.release') }}"><i class="fa fa-upload"></i> Release pending datasets</a>
                        </li>	
                        <li class="pure-menu-item {{ Route::is('publish.workflow.review') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{ URL::route('publish.workflow.review') }}"><i class="fa fa-upload"></i> Review/Publish unpublished datasets</a>
                        </li>			 --}}
                    </ul>
                </li>
                @endrole

               

              
                <li class="treeview">
                    <h2 class="pure-menu-heading">                       
                        <span>{{ trans('menus.backend.access.title') }}</span>
                        <i class="fa fa-angle-down"></i>
                    </h2>    
                    {{-- <a href="#">
                        <i class="fa fa-users"></i>
                        <span>{{ trans('menus.backend.access.title') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>              --}}
                    <ul class="pure-menu-list treeview-menu {{ active_class(Route::is('access.*'), 'menu-open') }}" style="display: none; {{ active_class(Route::is('access.*'), 'display: block;') }}">
                        @if (Auth::guest())
                        <li class="pure-menu-item {{ Route::currentRouteName() == 'login' ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{ route('login') }}">LOGIN</a>
                        </li>
                        @else  
                        @permission('settings')
                        <li class="pure-menu-item {{ Route::is('access.user.*') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{route('access.user.index') }}"><i class="fa fa-users"></i> User Management</a>
                        </li>
                        <li class="pure-menu-item {{ Route::is('access.role.*') ? 'active' : '' }}">
                            <a class="pure-menu-link" href="{{route('access.role.index') }}"><i class="fa fa-key"></i> Role Management</a>
                        </li>
                        <li class="pure-menu-item">
                            <a class="pure-menu-link" href="{{ route('access.user.edit',['id'=>Auth::user()->id]) }}"><i class="fa fa-user"></i> EDIT</a> 
                        </li>	  
                        @endpermission
                        <li class="pure-menu-item">
                            <a class="pure-menu-link" href="{{ route('logout') }}"><i class="fa fa-sign-out"></i>Logout</a>
                        </li> 
                        @endif
                    </ul>
                </li>
              

            </ul>
        </nav>
   
    </div>

    <div id="main">
        <div class="header">
            <div class="pure-g">
                <div class="pure-u-1-2">
                    <h1>Dashboard</h1>                  
                </div>
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
                <section class="content-header">
                    @yield('page-header')
                    {{-- <div class="breadcrumb">
                        <i class="fa fa-home"></i><a href="#" rel="Dashboard">Dashboard</a>
                        <i class="fa fa-angle-right"></i><a href="#" rel="Dashboard">Sales</a>
                    </div> --}}
                    {{-- @yield('breadcrumbs') --}}                   
                    <!-- Breadcrumbs would render from routes/breadcrumb.php -->
                    @if(Breadcrumbs::exists())
                        {!! Breadcrumbs::render() !!}
                    @endif
                </section>
            
                <section class="l-box">		
                            @include('partials.flash')
                            @yield('content')						
                </section>
            </div>
        </div>
    </div>

<!-- JavaScripts -->
@yield('before-scripts')
<script type="text/javascript" src="{{ asset('js/lib.js') }}"></script>
<script type="text/javascript">
    $('div.alert').not('alert-important');//.delay(3000).slideUp(300);

    // A $( document ).ready() block.
    $(document).ready(function() {
        $(document).on("keydown", function (e) {
            if (e.which === 8 && !$(e.target).is("input:not([type=radio]):not([type=checkbox]), textarea, [contentEditable], [contentEditable=true]")) {
                e.preventDefault();
            }
	    });
    });
</script>
<script type="text/javascript" src="{{ asset('backend/functions.js') }}"></script>
@yield('after-scripts')
</div>

</body>
</html>