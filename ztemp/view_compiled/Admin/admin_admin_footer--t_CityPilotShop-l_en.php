<?php /* Smarty version 2.6.28, created on 2017-10-29 15:36:49
         compiled from admin_admin_footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modgetinfo', 'admin_admin_footer.tpl', 2, false),)), $this); ?>
</div> <?php echo smarty_function_modgetinfo(array('modname' => $this->_tpl_vars['toplevelmodule'],'info' => 'all','assign' => 'toplevelinfo'), $this);?>

<div class="z-admin-coreversion z-right"><?php echo $this->_tpl_vars['toplevelinfo']['name']; ?>
 <?php echo $this->_tpl_vars['toplevelinfo']['version']; ?>
 / Zikula <?php echo $this->_tpl_vars['coredata']['version_num']; ?>
</div>