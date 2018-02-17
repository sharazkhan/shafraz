<?php /* Smarty version 2.6.28, created on 2017-10-02 20:36:23
         compiled from master.tpl */

$this->_cache_serials['ztemp/Theme_compiled/Atom2/master.inc'] = '7894889ecd45e3b96c3f870830a7e536';

?><?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'nocache', 'master.tpl', 1, false),array('function', 'charset', 'master.tpl', 3, false),array('function', 'getcurrenturl', 'master.tpl', 7, false),array('function', 'pagegetvar', 'master.tpl', 8, false),array('function', 'id', 'master.tpl', 10, false),array('function', 'updated', 'master.tpl', 11, false),)), $this); ?>
<?php
function _smarty_tplfunc_7894889ecd45e3b96c3f870830a7e536_0(&$_smarty)
{
$_smarty->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], null, $_smarty, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php header("Content-type: application/atom+xml"); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($_smarty->_tag_stack[count($_smarty->_tag_stack)-1][1], $_block_content, $_smarty, $_block_repeat); }  array_pop($_smarty->_tag_stack); 
}



?>
