<?php /* Smarty version 2.6.28, created on 2017-10-10 23:45:58
         compiled from findus.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'blockposition', 'findus.tpl', 19, false),)), $this); ?>
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
    <div class="banner">
         <?php echo smarty_function_blockposition(array('name' => "minisite-banner"), $this);?>

        </div>
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

                    <div class="contents-box clearfix product-details">
                    	<div class="clearfix product-details-wrapper">
                            <div class="col-sm-12">
                                <?php echo $this->_tpl_vars['maincontent']; ?>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- shop sidebar -->
                <div class="col-md-4 contents-right">
                    <div class="clearfix">
                          <div class="shopaddress">
                            <?php echo smarty_function_blockposition(array('name' => 'shopaddress'), $this);?>

                            </div>
                    </div>
                </div>
                <!-- end shop sidebar -->
            </div>
            <!-- Special End -->

            <!-- Employees  -->
           
               
            <div class="employees">
                        <?php echo smarty_function_blockposition(array('name' => 'employees'), $this);?>
 
            </div>
            <!-- employees -->


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