<?php /* Smarty version 2.6.28, created on 2017-10-10 21:45:52
         compiled from admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'homepage', 'admin.tpl', 5, false),array('function', 'gt', 'admin.tpl', 5, false),array('function', 'modurl', 'admin.tpl', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'includes/header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div id="theme_navigation_bar">
    <ul class="z-clearfix">
        <li><a href="<?php echo smarty_function_homepage(array(), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Home'), $this);?>
</a></li>
        <li><a href="<?php echo smarty_function_modurl(array('modname' => 'Settings','type' => 'admin','func' => 'main'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Settings'), $this);?>
</a></li>
        <li><a href="<?php echo smarty_function_modurl(array('modname' => 'Extensions','type' => 'admin','func' => 'main'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Extensions'), $this);?>
</a></li>
        <li><a href="<?php echo smarty_function_modurl(array('modname' => 'Blocks','type' => 'admin','func' => 'main'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Blocks'), $this);?>
</a></li>
        <li><a href="<?php echo smarty_function_modurl(array('modname' => 'Users','type' => 'admin','func' => 'main'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Users'), $this);?>
</a></li>
        <li><a href="<?php echo smarty_function_modurl(array('modname' => 'Groups','type' => 'admin','func' => 'main'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Groups'), $this);?>
</a></li>
        <li><a href="<?php echo smarty_function_modurl(array('modname' => 'Permissions','type' => 'admin','func' => 'main'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Permission rules'), $this);?>
</a></li>
        <li><a href="<?php echo smarty_function_modurl(array('modname' => 'Theme','type' => 'admin','func' => 'main'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Themes'), $this);?>
</a></li>
    </ul>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "body/".($this->_tpl_vars['admin']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'includes/footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>