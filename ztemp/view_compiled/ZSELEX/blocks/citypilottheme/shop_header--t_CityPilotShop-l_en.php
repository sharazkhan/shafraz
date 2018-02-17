<?php /* Smarty version 2.6.28, created on 2017-10-10 23:49:13
         compiled from blocks/citypilottheme/shop_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cartcount2', 'blocks/citypilottheme/shop_header.tpl', 1, false),array('function', 'blockposition', 'blocks/citypilottheme/shop_header.tpl', 8, false),array('function', 'modurl', 'blocks/citypilottheme/shop_header.tpl', 26, false),array('function', 'homepage', 'blocks/citypilottheme/shop_header.tpl', 35, false),)), $this); ?>
<?php echo smarty_function_cartcount2(array(), $this);?>

<header class="header"><!-- header start -->
        <div class="top-bar">
            <div class="container">
                <!-- Collect the nav links, forms, and other content for toggling -->
               <div class="collapse navbar-collapse top-menu" id="bs-example-navbar-collapse-1">
                <div class="nav navbar-nav navbar-right">
                    <?php echo smarty_function_blockposition(array('name' => 'verytop-right'), $this);?>


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
                      <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'cart'), $this);?>
" class="mob-cart shop-cart">
                          <span class="btn btn-default shopping-bag"><span id="carts_total"><span class="carts_total"><?php echo $this->_tpl_vars['cartCount']; ?>
</span><span class="dkk"> DKK</span></span> <i class="fa fa-shopping-bag"></i></span>
                </a>
                    <div class="search-icon">
                        <i class="fa fa-search"></i>
                    </div>
                     <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'cart'), $this);?>
" class="mob-cart">
                         <span class="btn btn-default shopping-bag"><span id="carts_total"><span class="carts_total"><?php echo $this->_tpl_vars['cartCount']; ?>
</span><span class="dkk"> DKK</span></span> <i class="fa fa-shopping-bag"></i></span>
                </a>
                    <a class="navbar-brand" href="<?php echo smarty_function_homepage(array(), $this);?>
"><img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/shop-logo.png" alt="" width="151" height="35"></a>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
        <!-- Navigation End -->

        <!-- Search -->
        <div class="selection-box">
       <?php echo smarty_function_blockposition(array('name' => 'selectionbox'), $this);?>

        </div>
        <!-- Search End -->
    </header>