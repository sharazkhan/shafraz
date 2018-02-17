<?php /* Smarty version 2.6.28, created on 2017-10-02 15:21:29
         compiled from includes/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date', 'includes/footer.tpl', 4, false),array('function', 'homepage', 'includes/footer.tpl', 4, false),array('function', 'sitename', 'includes/footer.tpl', 4, false),array('function', 'gt', 'includes/footer.tpl', 4, false),array('function', 'modurl', 'includes/footer.tpl', 5, false),)), $this); ?>
<div id="footer">
<p>
	<span style="float:left;padding-left:5px;">			
		<!-- &copy;&nbsp;2012-<?php echo ((is_array($_tmp='Y')) ? $this->_run_mod_handler('date', true, $_tmp) : date($_tmp)); ?>
&nbsp;<a href="<?php echo smarty_function_homepage(array(), $this);?>
" title="<?php echo smarty_function_sitename(array(), $this);?>
&nbsp;<?php echo smarty_function_gt(array('text' => 'Home Page'), $this);?>
"><strong><?php echo smarty_function_sitename(array(), $this);?>
</strong></a>&nbsp;|&nbsp;
		<a href="<?php echo smarty_function_modurl(array('modname' => 'formicula'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Contact'), $this);?>
</a>&nbsp;|&nbsp;
		<a href="<?php echo smarty_function_modurl(array('modname' => 'Sitemap'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'SiteMap'), $this);?>
</a>&nbsp;|&nbsp;
		<a href="<?php echo smarty_function_modurl(array('modname' => 'News','func' => 'view','theme' => 'rss'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'RSS Feed'), $this);?>
</a> -->
	</span>
	<span style="float:right;text-align:right;padding-right:5px;">
	</span>
</p>
</div>