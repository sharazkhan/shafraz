<?php /* Smarty version 2.6.28, created on 2017-12-10 11:34:30
         compiled from master.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'blockposition', 'master.tpl', 9, false),array('insert', 'getstatusmsg', 'master.tpl', 20, false),)), $this); ?>
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
    
    <!-- Banner End -->

    <!-- Contents -->
    <section class="contents-wrap">
        <div class="container">
            <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'getstatusmsg')), $this); ?>

                 <?php echo $this->_tpl_vars['maincontent']; ?>

          
        </div> <!-- Container Ends -->
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