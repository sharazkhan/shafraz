 <header class="header">
        <div class="top-bar">
            <div class="container">
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse top-menu" id="bs-example-navbar-collapse-1">
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
                    <a class="navbar-brand" href="index.html"><img src="{$themepath}/images/Logo.png" alt="" width="151" height="35"></a>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
        <!-- Navigation End -->

        <!-- Search -->
        <section class="search-wrap">
            <div class="container">
                <div class="form-inline clearfix">
                    <div class="mobi-controls">
                        <div class="region-select inline-select">
                            <select id="selectbox1" class="chosen-select-search form-control">
                               <option value="">Søg efter region</option>
                               <option value="8">MIDTJYLLAND</option>
                               <option value="9">NORDJYLLAND</option>
                               <option value="7">SYDJYLLAND</option>
                               <option value="10">SJÆLLAND</option>
                               <option value="11">HOVEDSTADSOMRÅDET</option>
                               <option value="14">FYN</option>
                               <option value="15">BORNHOLM</option>
                            </select>
                        </div>
                        <div class="city-select inline-select">
                            <select id="selectbox2" class="chosen-select-search form-control">
                                <option value="">søg efter by</option>
                            </select>
                        </div>
                        <div class="category-select inline-select">
                            <select id="selectbox3" class="chosen-select-search form-control">
                                <option value="">Søg efter kategori</option>
                                <option value="5">Andet</option>
                                <option value="77">Apotek</option>
                               
                            </select>
                        </div>
                        <input type="text" class="form-control" placeholder="Søg efter...">
                    </div>

                    <button class="btn btn-primary search-btn">Vis butikker <i class="fa fa-angle-double-right"></i></button>
                    <span class="btn btn-default reset-btn"><i class="fa fa-times"></i> nulstil</span>
                    <span class="btn btn-default shopping-bag">0,00 DKK <i class="fa fa-shopping-bag"></i></span>
                </div>
            </div>
        </section>
        <!-- Search End -->
    </header>