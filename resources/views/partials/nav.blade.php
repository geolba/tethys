<div class="header" id="menu" itemprop="hasPart" itemscope itemtype="http://schema.org/WPHeader">
    <div class="container">
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
                                    <li class="pure-menu-item {{ Route::currentRouteName() == 'home.index' ? 'active' : '' }}">
                                        <a class="pure-menu-link" href="{{ url('/') }}">HOME</a>
                                    </li>
                                   
                                    <!-- <li><a class="marvel" href="{{ url('books') }}">BOOKS</a></li> -->
                                    @if(Auth::user())

                                    <li class="pure-menu-item pure-menu-allow-hover custom-can-transform">
                                        <a href="#" class="pure-menu-link"> SETTINGS <span class="fa fa-angle-down"></span></a>
                                        <ul class="pure-menu-children" role="menu">
                                            <!--<li class="pure-menu-item"><a href="{{ route('settings.book') }}" class="pure-menu-link">BOOK</a></li>-->
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('settings.document') }}" >DATASET</a></li>
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('settings.collection') }}">COLLECTION</a></li>
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('settings.project') }}">PROJECT</a></li>

                                            <!-- <li><a href="{{ route('settings.shelf') }}" class="marvel">SHELF</a></li>-->


                                            <li class="pure-menu-item"><a href="{{ route('settings.license') }}" class="pure-menu-link">LICENSES</a></li>
                                            <li class="pure-menu-item"><a href="{{ route('settings.person') }}" class="pure-menu-link">PERSON</a></li>

                                            <!-- <li><a href="{{ route('settings.periode') }}" class="marvel">PERIODE</a></li> -->
                                        </ul>
                                    </li>

                                    @else

                                    <li class="pure-menu-item {{ Route::currentRouteName() == 'search.index' ? 'active' : '' }}">
                                        <a class="pure-menu-link" href="{{ route('search.index') }}">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                            SEARCH
                                        </a>
                                    </li>
                                    <li class="pure-menu-item pure-menu-allow-hover">
                                        <a href="#" class="pure-menu-link">
                                            <i class="fa fa-bars"></i>
                                            MENU
                                            <span class="fa fa-angle-down"></span>
                                        </a>
                                        <ul class="pure-menu-children" role="menu">
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ URL::route('oai') }}" target="_blank"> OAI-PMH 2.0</a></li>
                                            {{-- <li class="pure-menu-item"><a class="pure-menu-link" href="{{ URL::route('dataset.create1') }}">PUBLISH</a></li> --}}
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ URL::route('home.news') }}">NEWS</a></li>
                                            <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('documents') }}">DATASETS</a></li>
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
                                <a class="pure-menu-link" href="{{ route('login') }}">LOGIN</a>
                            </li>
                            <!--<li class="pure-menu-item"><a class="pure-menu-link" href="{{ url('register') }}">REGISTER</a></li>-->
                            @else
                            <li class="pure-menu-item pure-menu-allow-hover custom-can-transform">
                                <a href="#" class="pure-menu-link">{{ Auth::user()->login }} <span class="fa fa-angle-down"></span></a>
                                <ul class="pure-menu-children" role="menu">
                                    <li class="pure-menu-item"><a class="pure-menu-link" href="{{route('user.edit',['id'=>Auth::user()->id]) }}"><i class="fa fa-user"></i> EDIT</a> </li>
                                     @hasrole('administrator')
                                    <li class="pure-menu-item"><a class="pure-menu-link" href="{{route('user.index') }}"><i class="fa fa-users"></i> EDIT USERS</a></li>
                                    <li class="pure-menu-item"><a class="pure-menu-link" href="{{route('role.index') }}"><i class="fa fa-key"></i> EDIT ROLES</a></li>
                                    @endhasrole
                                    <li class="pure-menu-item"><a class="pure-menu-link" href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> LOG OUT</a></li>
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


