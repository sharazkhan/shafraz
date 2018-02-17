<?php /* Smarty version 2.6.28, created on 2018-02-03 06:49:23
         compiled from plugin/fblike.tpl */

$this->_cache_serials['ztemp/view_compiled/Socialise/plugin/fblike--t_CityPilotShop-l_en.inc'] = '6099a40aa5084d8a5f79fbbfb26ba351';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'nocache', 'plugin/fblike.tpl', 1, false),array('modifier', 'urlencode', 'plugin/fblike.tpl', 2, false),)), $this); ?>
<?php
function _smarty_tplfunc_6099a40aa5084d8a5f79fbbfb26ba351_0(&$_smarty)
{
$_smarty->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo ((is_array($_tmp=$_smarty->_tpl_vars['plugin']['url'])) ? $_smarty->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&amp;layout=<?php echo $_smarty->_tpl_vars['plugin']['layout']; ?>
&amp;show_faces=<?php echo $_smarty->_tpl_vars['plugin']['faces']; ?>
&amp;width=<?php echo $_smarty->_tpl_vars['plugin']['width']; ?>
&amp;height=<?php echo $_smarty->_tpl_vars['plugin']['height']; ?>
&amp;action=<?php echo $_smarty->_tpl_vars['plugin']['action']; ?>
<?php if ($_smarty->_tpl_vars['plugin']['font']): ?>&amp;font=<?php echo $_smarty->_tpl_vars['plugin']['font']; ?>
<?php endif; ?>&amp;colorscheme=<?php echo $_smarty->_tpl_vars['plugin']['colorscheme']; ?>
<?php if ($_smarty->_tpl_vars['plugin']['ref']): ?>&amp;ref=<?php echo $_smarty->_tpl_vars['plugin']['ref']; ?>
<?php endif; ?>" scrolling="no" frameborder="0" style="border: none; overflow: hidden; width: <?php echo $_smarty->_tpl_vars['plugin']['width']; ?>
px; height: <?php echo $_smarty->_tpl_vars['plugin']['height']; ?>
px; vertical-align: middle"></iframe>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}



?>
