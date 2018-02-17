<?php /* Smarty version 2.6.28, created on 2017-09-30 14:18:15
         compiled from includes/footer.tpl */

$this->_cache_serials['ztemp/Theme_compiled/Andreas08/includes/footer.inc'] = '0b495142d300ff1851068def80f2a51a';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'includes/footer.tpl', 2, false),array('function', 'modurl', 'includes/footer.tpl', 2, false),array('function', 'pagerendertime', 'includes/footer.tpl', 3, false),array('block', 'nocache', 'includes/footer.tpl', 3, false),)), $this); ?>
<?php
function _smarty_tplfunc_0b495142d300ff1851068def80f2a51a_0(&$_smarty)
{
$_smarty->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo smarty_function_pagerendertime(array(), $_smarty);?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}



?>
