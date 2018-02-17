<?php /* Smarty version 2.6.28, created on 2017-10-01 14:41:56
         compiled from includes/footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'blockposition', 'includes/footer.tpl', 5, false),array('function', 'homepage', 'includes/footer.tpl', 20, false),array('function', 'sitename', 'includes/footer.tpl', 20, false),array('function', 'gt', 'includes/footer.tpl', 20, false),array('function', 'modurl', 'includes/footer.tpl', 20, false),array('modifier', 'date', 'includes/footer.tpl', 20, false),)), $this); ?>
<div style="clear:both;"></div>
<div id="footerbg">
	<div id="footer">
		<div id="footerleft">
			<?php echo smarty_function_blockposition(array('name' => 'footerleft'), $this);?>

		</div>
		<div id="footermiddle">
			<?php echo smarty_function_blockposition(array('name' => 'footermiddle'), $this);?>

		</div>
		<div id="footerright">
			<?php echo smarty_function_blockposition(array('name' => 'footerright'), $this);?>

		</div>
		<div style="clear:both;"></div>
	</div>
</div>
<div id="credits">


<p>	<span style="float:left;">
<!-- &copy;&nbsp;2012-<?php echo ((is_array($_tmp='Y')) ? $this->_run_mod_handler('date', true, $_tmp) : date($_tmp)); ?>
&nbsp;<a href="<?php echo smarty_function_homepage(array(), $this);?>
" title="<?php echo smarty_function_sitename(array(), $this);?>
&nbsp;<?php echo smarty_function_gt(array('text' => 'Home Page'), $this);?>
"><strong><?php echo smarty_function_sitename(array(), $this);?>
</strong></a>&nbsp;|&nbsp;<a href="<?php echo smarty_function_modurl(array('modname' => 'Sitemap'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'Sitemap'), $this);?>
</a>&nbsp;|&nbsp;<a href="<?php echo smarty_function_modurl(array('modname' => 'News','type' => 'user','func' => 'view','theme' => 'rss'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'RSS Feed'), $this);?>
</a> -->
</span>



	<span style="float:right;text-align:right;">
	</span></p>
<br />&nbsp;

</div>

