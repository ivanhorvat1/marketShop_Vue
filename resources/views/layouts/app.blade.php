<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    {{--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">--}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
          integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">--}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    {{--<meta name="user-id" content="{{ Auth::user() }}">--}}

    <title>Najjeftinije</title>
</head>
<body>
<div id="app">
    <div>
        {{--<b-navbar toggleable="lg" type="dark">
            <b-navbar-brand href="/"><img center src="images/Logo6.png" style="height: 150px; width: 150px"></b-navbar-brand>

            <b-navbar-toggle target="nav-collapse" style="background-color: #591259"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item href="/"><i class="fas fa-home fa-3x"></i><span class="menuspan">Home</span></b-nav-item>
                    <b-nav-item href="/action"><i class="fas fa-percentage fa-3x"></i><span class="menuspan">Akcija</span></b-nav-item>
                    <b-nav-item href="/freeze"><i class="fas fa-snowflake fa-3x"></i><span class="menuspan">Smrznuto</span></b-nav-item>
                    <b-nav-item href="/drinks"><i class="fas fa-glass-cheers fa-3x"></i><span class="menuspan">Pice</span></b-nav-item>
                    <b-nav-item href="/sweets"><i class="fas fa-candy-cane fa-3x"></i><span class="menuspan">Slatkisi</span></b-nav-item>
                    <b-nav-item href="/meats"><i class="fas fa-drumstick-bite fa-3x"></i><span class="menuspan">Meso</span></b-nav-item>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">

                    <div class="dropdown">
                        @guest
                            <button style="background-color: #153607" class="btn btn-primary dropdown-toggle" type="button"
                                    data-toggle="dropdown">Login/Register
                                <span class="caret"></span></button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                                <a class="dropdown-item" href="{{ route('register') }}">Register</a>
                            </div>
                        @else
                            <button style="background-color: #153607" class="btn btn-primary dropdown-toggle" type="button"
                                    data-toggle="dropdown">{{Auth::user()->name}}
                                <span class="caret"></span></button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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

                    </div>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>--}}
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
                        <a class="nav-link" href="/"><i class="fas fa-home fa-3x"></i>
                            {{--<span class="menuspan">Home</span>--}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/action"><i class="fas fa-percentage fa-3x"></i>
                            {{--<span class="menuspan">Akcija</span>--}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/freeze"><i class="fas fa-snowflake fa-3x"></i>
                            {{--<span class="menuspan">Smrznuto</span>--}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/drinks"><i class="fas fa-glass-cheers fa-3x"></i>
                            {{--<span class="menuspan">Pice</span>--}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/sweets"><i class="fas fa-candy-cane fa-3x"></i>
                            {{--<span class="menuspan">Slatkisi</span>--}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/meats"><i class="fas fa-drumstick-bite fa-3x"></i>
                            {{--<span class="menuspan">Meso</span>--}}
                        </a>
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
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        @endguest
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript">
    $(window).on('load', function () {
        var current = location.pathname;
        /*$('nav li a').each(function () {
            var $this = $(this);
            // if the current path is like this link, make it active
            if ($this.attr('href') === current) {
                $this.children().addClass("activeMenu");
            }
        });*/

        $('.nav-item a').each(function () {
            var $this = $(this);
            // if the current path is like this link, make it active
            if ($this.attr('href') === current) {
                $this.children().addClass("activeMenu");
            }

            $this.hover(function () {
                var href = $this.attr('href');
                if (href === '/drinks') {
                    $('nav').css("background-image", 'url("images/menu_images/drink_menu_right.jpg")').css("background-repeat", 'no-repeat');
                }else if(href === '/meats'){
                    $('nav').css("background-image", 'url("images/menu_images/meats_menu_right1.jpg")').css("background-repeat", 'no-repeat');
                }else if(href === '/sweets'){
                    $('nav').css("background-image", 'url("images/menu_images/sweets_menu_right1.jpg")').css("background-repeat", 'no-repeat');
                }else if(href === '/freeze'){
                    $('nav').css("background-image", 'url("images/menu_images/freeze_menu_right.jpg")').css("background-repeat", 'no-repeat');
                }else{
                    $('nav').css("background-image", "");
                }
            });

        });

        /*$(window).resize(function() {
            var width = $(window).width();
            if (width < 1200){
                $('.nav-item a').each(function () {
                    var $this = $(this);
                    // if the current path is like this link, make it active
                    if ($this.attr('href') === current) {
                        $this.children().addClass("activeMenu");
                    }

                    $this.hover(function () {
                        var href = $this.attr('href');
                        if (href === '/drinks') {
                            $('nav').css("background-image", 'url("images/menu_images/drink_menu_right_mobile.jpg")').css("background-repeat", 'no-repeat');
                        }else if(href === '/meats'){
                            $('nav').css("background-image", 'url("images/menu_images/meats_menu_right.jpg")').css("background-repeat", 'no-repeat');
                        }else if(href === '/sweets'){
                            $('nav').css("background-image", 'url("images/menu_images/sweets_menu_right.jpg")').css("background-repeat", 'no-repeat');
                        }else if(href === '/freeze'){
                            $('nav').css("background-image", 'url("images/menu_images/freeze_menu_right.jpg")').css("background-repeat", 'no-repeat');
                        }else{
                            $('nav').css("background-image", "");
                        }
                    });

                });
            }
        });*/
    });

    window.onbeforeunload = function () {
        window.scrollTo(0, 0);
    };
</script>
</body>
</html>
