<!doctype html>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js"></script>

    {{--<meta name="user-id" content="{{ Auth::user() }}">--}}

    <title>Najjeftinije</title>
    <!-- Fonts -->
    {{--<link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">--}}
</head>
<body>
<div id="app">
    <div id="preloader-wrapper">
        <div id="preloader"></div>
        <div class="preloader-section section-left"></div>
        <div class="preloader-section section-right"></div>
    </div>
    @include('includes.navbar')
    {{--<div class="pull-right">
        <!-- Authentication Links -->
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
    </div>--}}
    <div class="container">
        @if($showStore)
            @include('frontend.articlesHomePage')
        @else
            @yield('content')
        @endif
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>--}}
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
                    $('nav').css("background-image", 'url("images/menu_images/drink_menu_right.jpg")').css("background-repeat", 'no-repeat').css('background-size','cover');
                }else if(href === '/meats'){
                    $('nav').css("background-image", 'url("images/menu_images/meats_menu_right1.jpg")').css("background-repeat", 'no-repeat').css('background-size','cover');
                }else if(href === '/sweets'){
                    $('nav').css("background-image", 'url("images/menu_images/sweets_menu_right1.jpg")').css("background-repeat", 'no-repeat').css('background-size','cover');
                }else if(href === '/freeze'){
                    $('nav').css("background-image", 'url("images/menu_images/freeze_menu_right.jpg")').css("background-repeat", 'no-repeat').css('background-size','cover');
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