<template>
    <nav class="navbar navbar-expand-sm navbar-dark bg-info mb-2">
        <!--<a href="#" class="navbar-brand">Maxi Gotcha</a>-->
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/"><i class="fas fa-home fa-3x"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/freeze"><i class="fas fa-snowflake fa-3x"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/drinks"><i class="fas fa-glass-cheers fa-3x"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/sweets"><i class="fas fa-candy-cane fa-3x"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/meats"><i class="fas fa-drumstick-bite fa-3x"></i></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/dis">DIS</a>
                </li>
                <!--<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Dropdown link
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>-->
            </ul>
        </div>
    </nav>
</template>
<script type="text/javascript">
    $(document).ready(function () {
        var url = window.location;
        $('ul.nav a[href="'+ url +'"]').parent().addClass('active');
        $('ul.nav a').filter(function() {
            return this.href == url;
        }).parent().addClass('active');
    });
</script>