<?php /* Smarty version 2.6.28, created on 2018-02-03 08:17:10
         compiled from fconnect_auth_authenticationmethodselector.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'fconnect_auth_authenticationmethodselector.tpl', 2, false),array('function', 'modurl', 'fconnect_auth_authenticationmethodselector.tpl', 3, false),)), $this); ?>

<?php echo smarty_function_gt(array('text' => 'Google Account','assign' => 'button_text'), $this);?>

 <a href="<?php echo smarty_function_modurl(array('modname' => 'Google','type' => 'user','func' => 'main'), $this);?>
" id="authentication_select_method_google_google" class="authentication_select_method_button"><?php echo $this->_tpl_vars['button_text']; ?>
</a>