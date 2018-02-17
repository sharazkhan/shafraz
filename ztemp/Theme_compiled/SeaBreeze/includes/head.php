<?php /* Smarty version 2.6.28, created on 2017-09-30 14:39:21
         compiled from includes/head.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'charset', 'includes/head.tpl', 1, false),array('function', 'pagegetvar', 'includes/head.tpl', 2, false),array('function', 'pageaddvar', 'includes/head.tpl', 6, false),)), $this); ?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo smarty_function_charset(array(), $this);?>
" />
<title><?php echo smarty_function_pagegetvar(array('name' => 'title'), $this);?>
</title>
<meta name="description" content="<?php echo $this->_tpl_vars['metatags']['description']; ?>
" />
<meta name="keywords" content="<?php echo $this->_tpl_vars['metatags']['keywords']; ?>
" />
<meta http-equiv="X-UA-Compatible" content="chrome=1" />
<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => ($this->_tpl_vars['stylepath'])."/style.css"), $this);?>
