<?php /* Smarty version 2.6.28, created on 2018-02-17 19:13:53
         compiled from admin_admin_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'admincategorymenu', 'admin_admin_header.tpl', 1, false),array('function', 'modgetinfo', 'admin_admin_header.tpl', 4, false),array('function', 'modgetimage', 'admin_admin_header.tpl', 5, false),array('function', 'modulelinks', 'admin_admin_header.tpl', 8, false),array('modifier', 'safetext', 'admin_admin_header.tpl', 5, false),)), $this); ?>
<?php echo smarty_function_admincategorymenu(array(), $this);?>

<div class="z-admin-content z-clearfix">
    <div class="z-admin-content-modtitle">
	<?php echo smarty_function_modgetinfo(array('modname' => $this->_tpl_vars['toplevelmodule'],'info' => 'displayname','assign' => 'displayName'), $this);?>

	<img src="<?php echo ((is_array($_tmp=smarty_function_modgetimage(array(), $this))) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp));?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['displayName'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
" />
	<h2><?php echo $this->_tpl_vars['displayName']; ?>
</h2>
    </div>
    <?php echo smarty_function_modulelinks(array('modname' => $this->_tpl_vars['toplevelmodule'],'type' => 'admin'), $this);?>
