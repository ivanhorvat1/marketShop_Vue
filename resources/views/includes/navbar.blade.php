<div>
    <!--<nav style="height: 150px">
        <div class="nav-wrapper">
            <span class="brand-logo"><img center src="images/Logo6.png" style="height: 150px; width: 150px"></span>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger btn"><i class="material-icons">menu</i></a>
            <div class="container" style="padding-top: 20px">
                <ul class="hide-on-med-and-down">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-home fa-3x"></i><span class="menuspan">Home</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/action"><i
                                class="fas fa-percentage fa-3x"></i><span class="menuspan">Akcija</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/freeze"><i
                                class="fas fa-snowflake fa-3x"></i><span class="menuspan">Smrznuto</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/drinks"><i
                                class="fas fa-glass-cheers fa-3x"></i><span class="menuspan">Pice</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/sweets"><i
                                class="fas fa-candy-cane fa-3x"></i><span class="menuspan">Slatkisi</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/meats"><i
                                class="fas fa-drumstick-bite fa-3x"></i><span class="menuspan">Meso</span></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <ul class="sidenav" id="mobile-demo">
        <li class="nav-item">
            <a class="nav-link" href="/"><i class="fas fa-home fa-3x"></i><span>Home</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/action"><i class="fas fa-percentage fa-3x"></i><span>Akcija</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/freeze"><i class="fas fa-snowflake fa-3x"></i><span>Smrznuto</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/drinks"><i class="fas fa-glass-cheers fa-3x"></i><span>Pice</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/sweets"><i class="fas fa-candy-cane fa-3x"></i><span>Slatkisi</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/meats"><i class="fas fa-drumstick-bite fa-3x"></i><span>Meso</span></a>
        </li>
    </ul>-->
    <nav class="mb-1 navbar navbar-expand-lg navbar-dark default-color">
        <a class="navbar-brand" href="/"><img center src="images/Logo6.png" style="height: 150px; width: 150px"></a>
        <button style="background-color: #591259" class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent-333"
                aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/"><i class="fas fa-home fa-3x"></i><span class="menuspan">Home</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/action"><i class="fas fa-percentage fa-3x"></i><span class="menuspan">Akcija</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/freeze"><i class="fas fa-snowflake fa-3x"></i><span class="menuspan">Smrznuto</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/drinks"><i class="fas fa-glass-cheers fa-3x"></i><span class="menuspan">Pice</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/sweets"><i class="fas fa-candy-cane fa-3x"></i><span class="menuspan">Slatkisi</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/meats"><i class="fas fa-drumstick-bite fa-3x"></i><span class="menuspan">Meso</span></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto nav-flex-icons">
                {{--<li class="nav-item">
                    <a class="nav-link waves-effect waves-light">
                        <i class="fab fa-twitter"></i>
                    </a>
                </li>
                <li class="nav-item" >
                    <a class="nav-link waves-effect waves-light">
                        <i class="fab fa-google-plus-g"></i>
                    </a>
                </li>--}}
                <li class="nav-item avatar dropdown">
                    @guest
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false" style="color: #591259">
                            <img src="images/default_avatar.png" style="width: 50px; height: 50px;"
                                 class="rounded-circle z-depth-0"
                                 alt="avatar image">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-secondary"
                             aria-labelledby="navbarDropdownMenuLink-55">
                            <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                            <a class="dropdown-item" href="{{ route('register') }}">Register</a>
                        </div>
                    @else
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false" style="color: #591259">
                            <img src="images/default_avatar.png" style="width: 50px; height: 50px;"
                                 class="rounded-circle z-depth-0"
                                 alt="avatar image">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-secondary"
                             aria-labelledby="navbarDropdownMenuLink-55">
                            <a class="dropdown-item" href="{{ route('home') }}">Profile</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    @endguest
                </li>
            </ul>
        </div>
    </nav>
</div>

