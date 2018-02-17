<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:04
         compiled from user/article.tpl */

$this->_cache_serials['ztemp/view_compiled/News/user/article--t_CityPilotResponsive-l_en.inc'] = 'c8d8bad864aaf96d02128a8bac660394';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'ajaxheader', 'user/article.tpl', 1, false),array('function', 'pageaddvar', 'user/article.tpl', 5, false),array('function', 'setmetatag', 'user/article.tpl', 10, false),array('function', 'gt', 'user/article.tpl', 24, false),array('function', 'modurl', 'user/article.tpl', 30, false),array('function', 'notifydisplayhooks', 'user/article.tpl', 37, false),array('modifier', 'notifyfilters', 'user/article.tpl', 10, false),array('modifier', 'strip_tags', 'user/article.tpl', 10, false),array('modifier', 'trim', 'user/article.tpl', 10, false),array('modifier', 'truncate', 'user/article.tpl', 10, false),array('modifier', 'safehtml', 'user/article.tpl', 30, false),array('modifier', 'dateformat', 'user/article.tpl', 30, false),array('block', 'nocache', 'user/article.tpl', 13, false),array('insert', 'getstatusmsg', 'user/article.tpl', 14, false),)), $this); ?>
<?php
function _smarty_tplfunc_c8d8bad864aaf96d02128a8bac660394_0(&$_smarty)
{
$_smarty->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_smarty_tpl_vars = $_smarty->_tpl_vars;
$_smarty->_smarty_include(array('smarty_include_tpl_file' => 'user/menu.tpl', 'smarty_include_vars' => array()));
$_smarty->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}



?>
