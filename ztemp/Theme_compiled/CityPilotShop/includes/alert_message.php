<?php /* Smarty version 2.6.28, created on 2017-12-10 15:16:06
         compiled from includes/alert_message.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'blockposition', 'includes/alert_message.tpl', 2, false),)), $this); ?>
 <!-- alert -->
     <?php echo smarty_function_blockposition(array('name' => 'top-center','assign' => 'admin_msg'), $this);?>

     <?php if (! empty ( $this->_tpl_vars['admin_msg'] )): ?>
    <div role="alert" class="alert alert-warning alert-dismissible fade in top-alert">
        <div class="container">
            <button aria-label="Close" data-dismiss="alert" class="close" type="button">
                <span aria-hidden="true">×</span>
            </button>
                       <?php echo $this->_tpl_vars['admin_msg']; ?>

        </div>
    </div>
        <?php endif; ?>
    <!-- alert end -->