<?php /* Smarty version 2.6.28, created on 2017-10-01 14:41:53
         compiled from includes/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'homepage', 'includes/header.tpl', 5, false),array('function', 'gt', 'includes/header.tpl', 5, false),array('function', 'modurl', 'includes/header.tpl', 6, false),array('function', 'sitename', 'includes/header.tpl', 10, false),array('function', 'slogan', 'includes/header.tpl', 11, false),array('function', 'search', 'includes/header.tpl', 14, false),)), $this); ?>
<div id="topbar">
	<div id="topbarleft"> </div>
	<div id="topbarright">
	<ul>
	<li <?php if ($this->_tpl_vars['pagetype'] == 'home'): ?> class="current" <?php endif; ?>><a href="<?php echo smarty_function_homepage(array(), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Home'), $this);?>
</a></li>
	<li <?php if ($this->_tpl_vars['module'] == 'News'): ?> class="current" <?php endif; ?>><a href="<?php echo smarty_function_modurl(array('modname' => 'News'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'News'), $this);?>
</a></li>
	</ul>
	</div>

	<h1 id="sitename"><a href="<?php echo smarty_function_homepage(array(), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Home'), $this);?>
"><?php echo smarty_function_sitename(array(), $this);?>
</a></h1>
	<h2 id="slogan"><?php echo smarty_function_slogan(array(), $this);?>
</h2>

	<div id="searchform">
	<?php echo smarty_function_search(array('class' => 'search','search_class' => 'textbox','button_class' => 'button','button' => 'Search'), $this);?>

	</div>

</div>