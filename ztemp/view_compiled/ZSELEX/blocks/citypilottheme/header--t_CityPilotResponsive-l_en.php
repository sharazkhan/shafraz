<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:08
         compiled from blocks/citypilottheme/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'blocks/citypilottheme/header.tpl', 2, false),array('function', 'cartcount2', 'blocks/citypilottheme/header.tpl', 10, false),array('function', 'blockposition', 'blocks/citypilottheme/header.tpl', 33, false),array('function', 'modurl', 'blocks/citypilottheme/header.tpl', 54, false),array('function', 'homepage', 'blocks/citypilottheme/header.tpl', 57, false),)), $this); ?>
<!--  selection box js & css  -->
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => ($this->_tpl_vars['themepath'])."/javascript/searchlist.js?v=1.1"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => ($this->_tpl_vars['themepath'])."/javascript/selections.js"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => "modules/ZSELEX/javascript/selectioncookies.js"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => "modules/ZSELEX/javascript/jqueryautocomplete/js/jquery-ui-1.8.2.custom.min.js"), $this);?>
 

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "modules/ZSELEX/javascript/jqueryautocomplete/css/smoothness/jquery-ui-1.8.2.custom.css"), $this);?>


<!--  selection box js & css ends  -->
<?php echo smarty_function_cartcount2(array(), $this);?>

<header class="header">
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
                <div class="search-icon">
                    <i class="fa fa-search"></i>
                </div>
                <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'cart'), $this);?>
" class="mob-cart">
                <span class="btn btn-default shopping-bag"><span id="carts_total"><?php echo $this->_tpl_vars['cartCount']; ?>
<span class="dkk"> DKK</span></span> <i class="fa fa-shopping-bag"></i></span>
                </a>
                <a class="navbar-brand" href="<?php echo smarty_function_homepage(array(), $this);?>
"><img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/Logo.png" alt="" width="151" height="35"></a>
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