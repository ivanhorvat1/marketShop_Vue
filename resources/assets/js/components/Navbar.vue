<template>
    <nav class="navbar navbar-expand-md navbar-light mb-2">
        <ul class="nav navbar-nav navbar-logo">
            <li class="nav-item">
                <img center src="images/Logo6.png" style="height: 150px; width: 150px">
            </li>
        </ul>

        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#collapse_target" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation" style="background-color: #591259">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="container navbar-container">
            <div class="collapse navbar-collapse" id="collapse_target">
                <ul class="nav navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-home fa-3x"></i>
                            <p class="menu" align="center">Home</p></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/action"><i class="fas fa-percentage fa-3x"></i>
                            <p class="menu" align="center">Akcija</p></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/freeze"><i class="fas fa-snowflake fa-3x"></i>
                            <p class="menu" align="center">Smrznuto</p></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/drinks"><i class="fas fa-glass-cheers fa-3x"></i>
                            <p class="menu" align="center">Pice</p></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/sweets"><i class="fas fa-candy-cane fa-3x"></i>
                            <p class="menu" align="center">Slatkisi</p></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/meats"><i class="fas fa-drumstick-bite fa-3x"></i>
                            <p class="menu" align="center">Meso</p></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--<nav>
        <div class="nav-wrapper">
            <a href="#!" class="brand-logo">Logo</a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><a href="sass.html">Sass</a></li>
                <li><a href="badges.html">Components</a></li>
                <li><a href="collapsible.html">Javascript</a></li>
                <li><a href="mobile.html">Mobile</a></li>
            </ul>
        </div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
        <li><a href="sass.html">Sass</a></li>
        <li><a href="badges.html">Components</a></li>
        <li><a href="collapsible.html">Javascript</a></li>
        <li><a href="mobile.html">Mobile</a></li>
    </ul>-->
</template>
<script type="text/javascript">
    $(window).on('load', function () {
        var current = location.pathname;
        $('.nav li a').each(function () {
            var $this = $(this);
            // if the current path is like this link, make it active
            if ($this.attr('href') === current) {
                $this.children().addClass("activeMenu");
            }
        });
    });
</script>