<?php /* Smarty version 2.6.28, created on 2017-12-10 11:33:10
         compiled from home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'blockposition', 'home.tpl', 10, false),)), $this); ?>
<!DOCTYPE html>
<html lang="en">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <body class="home-page">
   
   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "includes/alert_message.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <!-- Header -->
      <div class="cp-header">
     <?php echo smarty_function_blockposition(array('name' => 'citypilotheader'), $this);?>

   </div>
    <!-- Header End -->

    <!-- Banner Slider-->
    <div class="exclusive-events">
         <?php echo smarty_function_blockposition(array('name' => 'exclusive_events'), $this);?>

    </div>
    <!-- Banner End -->

    <!-- Contents -->
    <section class="contents-wrap">
        <div class="container">

            <!-- Special Row -->
            <div class="row">
                
                <!-- Special Deals -->
                <div class="special-deal-block">
               <?php echo smarty_function_blockposition(array('name' => 'specialdeals'), $this);?>

                </div>
                <!-- Special Deal Ends -->
                <div class="upcomming-events">
                 <?php echo smarty_function_blockposition(array('name' => 'upcomming-events'), $this);?>

                </div>
                
            </div>
            <!-- Special End -->
            
            <!-- Sub Special Row  -->
            <div class="row new-shops">
                  <?php echo smarty_function_blockposition(array('name' => 'newshops'), $this);?>

            </div>
            <!-- Sub Special Row End-->
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