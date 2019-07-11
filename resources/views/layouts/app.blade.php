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
    <b-navbar toggleable="lg" type="dark">
        <b-navbar-brand href="/"><img center src="images/Logo6.png" style="height: 150px; width: 150px">
        </b-navbar-brand>

        <b-navbar-toggle target="nav-collapse" style="background-color: #591259"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav>
                <b-nav-item href="/"><i class="fas fa-home fa-3x"></i><span class="menuspan">Home</span></b-nav-item>
                <b-nav-item href="/action"><i class="fas fa-percentage fa-3x"></i><span class="menuspan">Akcija</span>
                </b-nav-item>
                <b-nav-item href="/freeze"><i class="fas fa-snowflake fa-3x"></i><span class="menuspan">Smrznuto</span>
                </b-nav-item>
                <b-nav-item href="/drinks"><i class="fas fa-glass-cheers fa-3x"></i><span class="menuspan">Pice</span>
                </b-nav-item>
                <b-nav-item href="/sweets"><i class="fas fa-candy-cane fa-3x"></i><span class="menuspan">Slatkisi</span>
                </b-nav-item>
                <b-nav-item href="/meats"><i class="fas fa-drumstick-bite fa-3x"></i><span class="menuspan">Meso</span>
                </b-nav-item>
            </b-navbar-nav>
        </b-collapse>
    </b-navbar>
    <div class="float-right">
        <!-- Authentication Links -->
        <div class="dropdown">
            @guest
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Login/Register
                    <span class="caret"></span></button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                    <a class="dropdown-item" href="{{ route('register') }}">Register</a>
                </div>
            @else
                <button class="btn btn-primary dropdown-toggle" type="button"
                        data-toggle="dropdown">{{Auth::user()->name}}
                    <span class="caret"></span></button>

                <a class="dropdown-item" href="{{ route('home') }}">Profile</a>
                <div class="form-group">
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
    </div>
    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
