<template>
    <div>
        <nav style="height: 150px">
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
        </ul>
    </div>
</template>
<script type="text/javascript">
    $(window).on('load', function () {
        var current = location.pathname;
        $('nav li a').each(function () {
            var $this = $(this);
            // if the current path is like this link, make it active
            if ($this.attr('href') === current) {
                $this.children().addClass("activeMenu");
            }
        });

        $('.nav-item a').each(function () {
            var $this = $(this);
            // if the current path is like this link, make it active
            if ($this.attr('href') === current) {
                $this.children().addClass("activeMenu");
            }
        });
    });
</script>