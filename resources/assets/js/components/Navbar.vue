<template>
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
        <b-navbar toggleable="lg" type="dark">
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
                <!--<b-navbar-nav class="ml-auto">
                    <b-nav-form>
                        <b-form-input size="sm" class="mr-sm-2" placeholder="Search"></b-form-input>
                        <b-button size="sm" class="my-2 my-sm-0" type="submit">Search</b-button>
                    </b-nav-form>

                    <b-nav-item-dropdown text="Lang" right>
                        <b-dropdown-item href="#">EN</b-dropdown-item>
                        <b-dropdown-item href="#">ES</b-dropdown-item>
                        <b-dropdown-item href="#">RU</b-dropdown-item>
                        <b-dropdown-item href="#">FA</b-dropdown-item>
                    </b-nav-item-dropdown>

                    <b-nav-item-dropdown right>
                        &lt;!&ndash; Using 'button-content' slot &ndash;&gt;
                        <template slot="button-content"><em>User</em></template>
                        <b-dropdown-item href="#">Profile</b-dropdown-item>
                        <b-dropdown-item href="#">Sign Out</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>-->
            </b-collapse>
        </b-navbar>
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