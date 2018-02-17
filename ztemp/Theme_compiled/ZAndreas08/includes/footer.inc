<?php /* Smarty version 2.6.28, created on 2017-10-01 12:25:19
         compiled from includes/footer.tpl */

$this->_cache_serials['ztemp/Theme_compiled/ZAndreas08/includes/footer.inc'] = '5d76d915f2d8c90e318b116cfd59e8aa';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'includes/footer.tpl', 2, false),array('function', 'modurl', 'includes/footer.tpl', 2, false),array('function', 'pagerendertime', 'includes/footer.tpl', 3, false),array('function', 'blockposition', 'includes/footer.tpl', 7, false),array('block', 'nocache', 'includes/footer.tpl', 3, false),)), $this); ?>
<?php
function _smarty_tplfunc_5d76d915f2d8c90e318b116cfd59e8aa_0(&$_smarty)
{
$_smarty->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo smarty_function_pagerendertime(array(), $_smarty);?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}



?>
