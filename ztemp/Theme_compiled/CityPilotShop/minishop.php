<?php /* Smarty version 2.6.28, created on 2017-10-10 23:43:43
         compiled from minishop.tpl */ ?>
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
                    <div class="col-md-12 contents-left">
                        <!-- Shop navigation -->
                         <?php if (! empty ( $_REQUEST['shop_id'] )): ?>
                       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/shopheader.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                         <?php endif; ?>  
                        <!-- Shop navigation End -->

                        <div class="contents-box clearfix product-list"> <!-- main content -->
                            <?php echo $this->_tpl_vars['maincontent']; ?>

                            
                        </div> <!-- main contend end -->
                    </div>
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