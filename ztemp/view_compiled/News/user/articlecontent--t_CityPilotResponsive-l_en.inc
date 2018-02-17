<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:04
         compiled from user/articlecontent.tpl */

$this->_cache_serials['ztemp/view_compiled/News/user/articlecontent--t_CityPilotResponsive-l_en.inc'] = 'a9ff8e3bf85b725d0abf1d11a8b2c048';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'safehtml', 'user/articlecontent.tpl', 13, false),array('modifier', 'dateformat', 'user/articlecontent.tpl', 22, false),array('modifier', 'notifyfilters', 'user/articlecontent.tpl', 35, false),array('block', 'nocache', 'user/articlecontent.tpl', 15, false),array('function', 'articleadminlinks', 'user/articlecontent.tpl', 16, false),array('function', 'gt', 'user/articlecontent.tpl', 22, false),array('function', 'modurl', 'user/articlecontent.tpl', 43, false),array('function', 'pager', 'user/articlecontent.tpl', 64, false),)), $this); ?>
<?php
function _smarty_tplfunc_a9ff8e3bf85b725d0abf1d11a8b2c048_0(&$_smarty)
{
$_smarty->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div id="news_editlinks"><?php echo smarty_function_articleadminlinks(array('sid' => $_smarty->_tpl_vars['info']['sid']), $_smarty);?>
</div>
<?php if ($_smarty->_tpl_vars['modvars']['News']['enableajaxedit']): ?>
<div id="news_editlinks_ajax" class="hidelink"><?php echo smarty_function_articleadminlinks(array('sid' => $_smarty->_tpl_vars['info']['sid'],'page' => $_smarty->_tpl_vars['page'],'type' => 'ajax'), $_smarty);?>
</div>
<?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}



?>
