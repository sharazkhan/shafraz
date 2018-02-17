<?php /* Smarty version 2.6.28, created on 2017-10-10 23:44:40
         compiled from ztextpage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'blockposition', 'ztextpage.tpl', 46, false),)), $this); ?>
<!DOCTYPE html>
<html lang="en">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <body class="shop-page">
        <!-- alert -->
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/alert_message.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <!-- alert end -->

        <!-- Header -->
        <div class="cp-header">
                   </div>
        <!-- Header End -->

        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/shopname.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

        <!-- Banner Slider-->
                <!-- Banner End -->

        <!-- Contents -->
        <section class="contents-wrap">
            <div class="container">

                <!-- Special Row -->
                <div class="row">
                    <div class="col-md-8 contents-left">
                        <!-- Shop navigation -->
                        <?php if (! empty ( $_REQUEST['shop_id'] )): ?>
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/shopheader.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                        <?php endif; ?>  
                        <!-- Shop navigation End -->

                        <div class="contents-box clearfix">
                            <?php echo $this->_tpl_vars['maincontent']; ?>

                        </div> <!-- main content ends -->
                    </div>

                    <!-- shop sidebar -->
                    <div class="col-md-4 contents-right">
                        <div class="clearfix">
                             <!-- shop address -->
                            <div class="shopaddress">
                            <?php echo smarty_function_blockposition(array('name' => 'shopaddress'), $this);?>

                            </div>
                            
                            <div class="sociallinks">
                            <?php echo smarty_function_blockposition(array('name' => 'sociallinks'), $this);?>

                           </div>
                            <!-- shop address ends -->
                            
                            <div class="page-index">
                           <?php echo smarty_function_blockposition(array('name' => 'page-index'), $this);?>

                            </div>
                        </div>
                    </div>
                    <!-- end shop sidebar -->
                </div>
                <!-- Special End -->
            </div>
        </section>
        <!-- Contents End -->

        <!-- footer -->
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <!-- Footer End -->


    </body>
</html>