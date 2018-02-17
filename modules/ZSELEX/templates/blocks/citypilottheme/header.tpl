<!--  selection box js & css  -->
{pageaddvar name='javascript' value="$themepath/javascript/searchlist.js?v=1.1"}
{pageaddvar name='javascript' value="$themepath/javascript/selections.js"}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/selectioncookies.js"}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/jqueryautocomplete/js/jquery-ui-1.8.2.custom.min.js"} 

{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/jqueryautocomplete/css/smoothness/jquery-ui-1.8.2.custom.css"}

<!--  selection box js & css ends  -->
{cartcount2}
<header class="header">
    <div class="top-bar">
        <div class="container">
            <!-- Collect the nav links, forms, and other content for toggling -->
            {*<div class="collapse navbar-collapse top-menu" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#">Om CityPilot </a>
                    </li>
                    <li>
                        <a href="#">Kontakt CityPilot</a>
                    </li>
                    <li>
                        <a href="#">Spørgsmål/Svar</a>
                    </li>
                    <li>
                        <a href="#">Log ind</a>
                    </li>
                </ul>
            </div>*}
            <div class="collapse navbar-collapse top-menu" id="bs-example-navbar-collapse-1">
                <div class="nav navbar-nav navbar-right">
                    {blockposition name='verytop-right'}

                </div>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    </div>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse main-nav" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="search-icon">
                    <i class="fa fa-search"></i>
                </div>
                <a href="{modurl modname='ZSELEX' type='user' func='cart'}" class="mob-cart">
                <span class="btn btn-default shopping-bag"><span id="carts_total">{$cartCount}<span class="dkk"> DKK</span></span> <i class="fa fa-shopping-bag"></i></span>
                </a>
                <a class="navbar-brand" href="{homepage}"><img src="{$themepath}/images/Logo.png" alt="" width="151" height="35"></a>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <!-- Navigation End -->

    <!-- Search -->
    <div class="selection-box">
        {blockposition name='selectionbox'}
    </div>
    <!-- Search End -->
</header>