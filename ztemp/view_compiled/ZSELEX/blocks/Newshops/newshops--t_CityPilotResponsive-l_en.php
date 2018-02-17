<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:10
         compiled from blocks/Newshops/newshops.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'blocks/Newshops/newshops.tpl', 4, false),)), $this); ?>
<input type="hidden" id="shopfrontorder" value="<?php echo $this->_tpl_vars['modvars']['ZSELEX']['shoporderby']; ?>
">
<input type="hidden" id="shopfrontlimit" value="<?php echo $this->_tpl_vars['modvars']['ZSELEX']['shopfrontlimit']; ?>
">
<div class="col-md-12">
                    <h3><?php echo smarty_function_gt(array('text' => 'New Shops'), $this);?>
</h3>
                </div>
<div id="newShopBlock">
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ajax/newshops.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
