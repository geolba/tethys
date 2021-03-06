<div class="header" id="menu" itemprop="hasPart" itemscope itemtype="http://schema.org/WPHeader">
    <div class="container">
            
        <div class="top-header">

            @include('components.langswitch', [
            'currentLocale' => App::getLocale(),
            'localesOrdered' => LaravelLocalization::getLocalesOrder(),
            'localizedURLs' => getLocalizedURLArray(),
            ])

            {{-- <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars fa-2x"></i>
            </button> --}}
            {{-- <a class="navbar-brand no-border hidden-xs first-part" href="{!! URL::to('') !!}">
                web
            </a> --}}

            {{-- @include('components.searchbar', [
                'search' => isSet($search) ? $search : '',
            ]) --}}
            {{-- <a class="navbar-brand no-border hidden-xs second-part" href="{!! URL::to('') !!}">
                umenia
            </a> --}}
           
        </div>


        <div class="inner-container">

            <div class="pure-hidden-tablet pure-hidden-desktop">
                <a href="#menu" id="menuLink" class="menu-link">
                    <!-- Hamburger icon -->
                    <span></span>
                </a>
            </div>

            <div class="pure-g">

                <div id="headerArea" class="pure-u-2-5 pure-u-md-10-24">
                    <h1 class="brand-title">
                        <a href="/" class="pure-menu-heading pure-menu-link">
                            <span class="e">Research Data Repository</span>
                        </a>
                    </h1>
                </div>

                <div class="pure-u-2-5 pure-u-md-11-24 pure-hidden-phone">
                    <div class="topmenu" id="topmenu">
                        <div id="topmenu-inner">
                            <nav class="pure-menu pure-menu-open pure-menu-horizontal">
                                <ul class="pure-menu-list">
                                    <li class="pure-menu-item {{ Route::currentRouteName() == 'frontend.home.index' ? 'active' : '' }}">                               
                                        <a class="pure-menu-link" href="{{ url('/') }}"><i class="fas fa-home"></i> HOME</a>
                                    </li>
                                   
                                    <!-- <li><a class="marvel" href="{{ url('books') }}">BOOKS</a></li> -->
                                    @if(Auth::user())

                                    <li class="pure-menu-item pure-menu-allow-hover custom-can-transform">
                                        <a href="#" class="pure-menu-link"> SETTINGS <span class="fa fa-angle-down"></span></a>
                                        <ul class="pure-menu-children" role="menu">
                                            <!--<li class="pure-menu-item"><a href="{{ route('settings.book') }}" class="pure-menu-link">BOOK</a></li>-->
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('settings.document') }}" >DATASET</a></li>
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('settings.collection.index') }}">COLLECTION</a></li>
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('settings.project') }}">PROJECT</a></li>

                                           


                                            <li class="pure-menu-item"><a href="{{ route('settings.license') }}" class="pure-menu-link">LICENSES</a></li>
                                            <li class="pure-menu-item"><a href="{{ route('settings.person') }}" class="pure-menu-link">PERSON</a></li>

                                            <!-- <li><a href="{{ route('settings.periode') }}" class="marvel">PERIODE</a></li> -->
                                        </ul>
                                    </li>

                                    @else

                                    <li class="pure-menu-item {{ Route::currentRouteName() == 'frontend.search.index' ? 'active' : '' }}">
                                        <a class="pure-menu-link" href="{{ route('frontend.search.index') }}">                                           
                                            <i class="fas fa-search" aria-hidden="true"></i>
                                            SEARCH
                                        </a>
                                    </li>
                                    <li class="pure-menu-item pure-menu-allow-hover">
                                        <a href="#" class="pure-menu-link">
                                            <i class="fas fa-bars"></i>
                                            MENU
                                            <span class="fas fa-angle-down"></span>
                                        </a>
                                        <ul class="pure-menu-children" role="menu">
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ URL::route('oai') }}" target="_blank"> OAI-PMH 2.0</a></li>
                                            {{-- <li class="pure-menu-item"><a class="pure-menu-link" href="{{ URL::route('dataset.create1') }}">PUBLISH</a></li> --}}
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ URL::route('frontend.home.news') }}">NEWS</a></li>
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('frontend.datasets') }}">DATASETS</a></li>
                                        </ul>
                                    </li>

                                    @endif

                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="pure-u-1-5 pure-u-md-3-24 pure-hidden-phone">
                    <nav class="pure-menu pure-menu-horizontal custom-menu-right">
                        <ul class="pure-menu-list">
                            @if (Auth::guest())
                            <li class="pure-menu-item {{ Route::currentRouteName() == 'login' ? 'active' : '' }}">
                                <a class="pure-menu-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> LOGIN</a>
                            </li>
                            <!--<li class="pure-menu-item"><a class="pure-menu-link" href="{{ url('register') }}">REGISTER</a></li>-->
                            @else
                            <li class="pure-menu-item pure-menu-allow-hover custom-can-transform">
                                <a href="#" class="pure-menu-link">{{ Auth::user()->login }} <span class="fa fa-angle-down"></span></a>
                                <ul class="pure-menu-children" role="menu">
                                    <li class="pure-menu-item"><a class="pure-menu-link" href="{{route('access.user.edit',['id'=>Auth::user()->id]) }}"><i class="fa fa-user"></i> EDIT</a> </li>
                                     @role('administrator')
                                    <li class="pure-menu-item"><a class="pure-menu-link" href="{{route('access.user.index') }}"><i class="fa fa-users"></i> EDIT USERS</a></li>
                                    <li class="pure-menu-item"><a class="pure-menu-link" href="{{route('access.role.index') }}"><i class="fa fa-key"></i> EDIT ROLES</a></li>
                                    @endrole
                                    <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> LOG OUT</a></li>
                                </ul>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>

</div>

<script>
    function toggleClass(element, className) {
        if (!element || !className) {
            return;
        }

        var classString = element.className, nameIndex = classString.indexOf(className);
        if (nameIndex == -1) {
            classString += ' ' + className;
        }
        else {
            classString = classString.substr(0, nameIndex) + classString.substr(nameIndex + className.length);
        }
        element.className = classString;
    }

    document.getElementById('menuLink').addEventListener('click', function () {
        toggleClass(document.getElementById('mobile-menu'), 'pure-hidden-phone');
    });
</script>


