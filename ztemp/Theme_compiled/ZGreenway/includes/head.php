<?php /* Smarty version 2.6.28, created on 2017-10-02 15:21:27
         compiled from includes/head.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'title', 'includes/head.tpl', 1, false),array('function', 'charset', 'includes/head.tpl', 2, false),array('function', 'sitename', 'includes/head.tpl', 4, false),array('function', 'slogan', 'includes/head.tpl', 5, false),array('function', 'keywords', 'includes/head.tpl', 6, false),array('function', 'modurl', 'includes/head.tpl', 16, false),array('modifier', 'date', 'includes/head.tpl', 7, false),)), $this); ?>
<title><?php echo smarty_function_title(array(), $this);?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo smarty_function_charset(array(), $this);?>
" />
<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
<meta name="Author" content="<?php echo smarty_function_sitename(array(), $this);?>
" />
<meta name="description" content="<?php echo smarty_function_slogan(array(), $this);?>
" />
<meta name="keywords" content="<?php echo smarty_function_keywords(array(), $this);?>
" />
<meta name="Copyright" content="Copyright (c) <?php echo ((is_array($_tmp='Y')) ? $this->_run_mod_handler('date', true, $_tmp) : date($_tmp)); ?>
 by <?php echo smarty_function_sitename(array(), $this);?>
" />
<meta name="Robots" content="index,follow" />

<link rel="icon" type="image/png" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/favicon.png" />
<link rel="icon" type="image/x-icon" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/favicon.ico" />
<link rel="shortcut icon" type="image/ico" href="<?php echo $this->_tpl_vars['imagepath']; ?>
/favicon.ico" />

<link rel="alternate" href="<?php echo smarty_function_modurl(array('modname' => 'News','type' => 'user','func' => 'view','theme' => 'rss'), $this);?>
" type="application/rss+xml" title="<?php echo smarty_function_sitename(array(), $this);?>
 Main Feed" />


<link rel="stylesheet" href="<?php echo $this->_tpl_vars['stylepath']; ?>
/style.css" type="text/css" media="screen,projection" />