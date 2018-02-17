<?php /* Smarty version 2.6.28, created on 2017-10-02 15:21:28
         compiled from includes/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'userwelcome', 'includes/header.tpl', 6, false),array('function', 'modurl', 'includes/header.tpl', 7, false),array('function', 'gt', 'includes/header.tpl', 7, false),array('function', 'homepage', 'includes/header.tpl', 14, false),array('function', 'sitename', 'includes/header.tpl', 29, false),array('function', 'slogan', 'includes/header.tpl', 30, false),array('modifier', 'ucwords', 'includes/header.tpl', 6, false),)), $this); ?>
<div id="top">

<div id="date">

<?php if ($this->_tpl_vars['loggedin'] == true): ?>
	<strong><?php echo ((is_array($_tmp=smarty_function_userwelcome(array(), $this))) ? $this->_run_mod_handler('ucwords', true, $_tmp) : ucwords($_tmp));?>
</strong>&nbsp;|&nbsp;
	<a href="<?php echo smarty_function_modurl(array('modname' => 'Users'), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'My Account Settings'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'My Account'), $this);?>
</a>&nbsp;|&nbsp;
	<a href="<?php echo smarty_function_modurl(array('modname' => 'Users','func' => 'logout'), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Logout of Your Account'), $this);?>
" class="last"><?php echo smarty_function_gt(array('text' => 'Logout'), $this);?>
</a>&nbsp;|&nbsp;
	<?php else: ?>

	<strong><em><?php echo ((is_array($_tmp=smarty_function_userwelcome(array(), $this))) ? $this->_run_mod_handler('ucwords', true, $_tmp) : ucwords($_tmp));?>
</em></strong>&nbsp;|&nbsp;<a href="<?php echo smarty_function_modurl(array('modname' => 'Users','func' => 'loginscreen'), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Log In to Your Account'), $this);?>
" class="last"><?php echo smarty_function_gt(array('text' => 'Log In'), $this);?>
</a>&nbsp;|&nbsp;
	<?php endif; ?>

<?php echo $this->_tpl_vars['datetime']; ?>
<?php if ($this->_tpl_vars['pagetype'] != 'home'): ?>&nbsp;::&nbsp;<a href="<?php echo smarty_function_homepage(array(), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Back to Main page'), $this);?>
</a><?php endif; ?>

</div>



<div id="icons">
<a href="<?php echo smarty_function_homepage(array(), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'Home'), $this);?>
"><img src="<?php echo $this->_tpl_vars['imagepath']; ?>
/home.gif" alt="<?php echo smarty_function_gt(array('text' => 'Home'), $this);?>
" /></a>
<a href="<?php echo smarty_function_modurl(array('modname' => 'Sitemap'), $this);?>
" title="<?php echo smarty_function_gt(array('text' => 'SiteMap'), $this);?>
"><img src="<?php echo $this->_tpl_vars['imagepath']; ?>
/sitemap.gif" alt="<?php echo smarty_function_gt(array('text' => 'SiteMap'), $this);?>
" /></a>
</div>


</div>

<div id="masthead">
	<span id="sitename"><?php echo smarty_function_sitename(array(), $this);?>
</span><br />
	<span id="slogan"><?php echo smarty_function_slogan(array(), $this);?>
</span>
</div>

	<div id="menu">
		<ul>
		<li <?php if ($this->_tpl_vars['pagetype'] == 'home'): ?> class="current" <?php endif; ?>><a href="<?php echo smarty_function_homepage(array(), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'Home'), $this);?>
</span></a></li>
		<li <?php if ($this->_tpl_vars['module'] == 'News'): ?> class="current" <?php endif; ?>><a href="<?php echo smarty_function_modurl(array('modname' => 'News'), $this);?>
"><span><?php echo smarty_function_gt(array('text' => 'News'), $this);?>
</span></a></li>
		</ul>
	</div>