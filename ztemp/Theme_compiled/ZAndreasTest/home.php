<?php /* Smarty version 2.6.28, created on 2017-10-02 16:16:58
         compiled from home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'blockposition', 'home.tpl', 3, false),array('function', 'homepage', 'home.tpl', 6, false),array('function', 'gt', 'home.tpl', 6, false),array('function', 'modurl', 'home.tpl', 7, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'includes/header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="theme_navigation_bar" class="z-clearfix">
    <?php echo smarty_function_blockposition(array('name' => 'topnav','assign' => 'topnavblock'), $this);?>

    <?php if (empty ( $this->_tpl_vars['topnavblock'] )): ?>
    <ul class="z-floatleft">
        <li><a href="<?php echo smarty_function_homepage(array(), $this);?>
" title="<?php echo smarty_function_gt(array('text' => "Go to the site's home page"), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Home'), $this);?>
</a></li>
        <li><a href="<?php echo smarty_function_modurl(array('modname' => 'Users','type' => 'user','func' => 'main'), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Go to your account panel'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'My Account'), $this);?>
</a></li>
        <li><a href="<?php echo smarty_function_modurl(array('modname' => 'Search','type' => 'user','func' => 'main'), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Search this site'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Site search'), $this);?>
</a></li>
    </ul>
    <?php else: ?>
    <?php echo $this->_tpl_vars['topnavblock']; ?>

    <?php endif; ?>
    <?php echo smarty_function_blockposition(array('name' => 'search'), $this);?>

</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "body/".($this->_tpl_vars['home']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'includes/footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>