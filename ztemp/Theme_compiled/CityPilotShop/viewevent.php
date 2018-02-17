<?php /* Smarty version 2.6.28, created on 2017-10-10 23:48:09
         compiled from viewevent.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'viewevent.tpl', 57, false),array('function', 'displayprice', 'viewevent.tpl', 68, false),)), $this); ?>
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

                        </div>
                    </div>

                    <!-- shop sidebar -->
                    <div class="col-md-4 contents-right">
                        <div class="event-date-time">
                            <ul>
                                <li>
                                    <i class="fa fa-calendar"></i>
                                    <?php echo $_REQUEST['start_weekday']; ?>
 <?php echo $_REQUEST['start_mday']; ?>
. <?php echo $_REQUEST['start_month']; ?>

                                    <br>
                                    <?php echo $_REQUEST['end_weekday']; ?>
 <?php echo $_REQUEST['end_mday']; ?>
. <?php echo $_REQUEST['end_month']; ?>

                                </li>
                                <li><i class="fa fa-clock-o"></i><?php echo $_REQUEST['shop_event_starthour']; ?>
 - <?php echo $_REQUEST['shop_event_endhour']; ?>
</li>
                                <li><i class="fa fa-map-marker"></i>
                                    <?php if ($_REQUEST['shop_event_venue'] != ''): ?> 
                                        <?php echo $_REQUEST['shop_event_venue']; ?>

                                    <?php else: ?>
                                        <?php if ($_REQUEST['showfrom'] == 'image'): ?>  
                                            <?php echo smarty_function_gt(array('text' => 'see event image'), $this);?>

                                        <?php else: ?>
                                            <?php echo smarty_function_gt(array('text' => 'see event'), $this);?>
  
                                        <?php endif; ?>   
                                    <?php endif; ?>
                                </li>
                                <li><i class="fa fa-envelope"></i><?php echo $_REQUEST['email']; ?>
</li>
                                <li><i class="fa fa-phone-square"></i><?php echo $_REQUEST['phone']; ?>
</li>
                                <li><i class="fa fa-money"></i>
                                    <?php if ($_REQUEST['price'] != ''): ?> 
                                        <?php if ($_REQUEST['price'] > 0): ?>
                                        <?php echo smarty_function_displayprice(array('amount' => $_REQUEST['price']), $this);?>

                                        <?php else: ?>
                                        <?php echo smarty_function_gt(array('text' => 'FREE'), $this);?>

                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($_REQUEST['showfrom'] == 'image'): ?>  
                                        <?php echo smarty_function_gt(array('text' => 'see event image'), $this);?>

                                        <?php else: ?>
                                        <?php echo smarty_function_gt(array('text' => 'see event'), $this);?>
  
                                        <?php endif; ?>   
                                    <?php endif; ?>
                                </li>
                            </ul>
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