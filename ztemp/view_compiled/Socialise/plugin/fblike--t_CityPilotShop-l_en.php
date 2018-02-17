<?php /* Smarty version 2.6.28, created on 2018-02-03 06:49:23
         compiled from plugin/fblike.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'nocache', 'plugin/fblike.tpl', 1, false),array('modifier', 'urlencode', 'plugin/fblike.tpl', 2, false),)), $this); ?>
<?php $this->_cache_serials['ztemp/view_compiled/Socialise/plugin/fblike--t_CityPilotShop-l_en.inc'] = '6099a40aa5084d8a5f79fbbfb26ba351'; ?><?php if ($this->caching && !$this->_cache_including): echo '{nocache:6099a40aa5084d8a5f79fbbfb26ba351#0}'; endif;$this->_tag_stack[] = array('nocache', array()); $_block_repeat=true;Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo ((is_array($_tmp=$this->_tpl_vars['plugin']['url'])) ? $this->_run_mod_handler('urlencode', true, $_tmp) : urlencode($_tmp)); ?>
&amp;layout=<?php echo $this->_tpl_vars['plugin']['layout']; ?>
&amp;show_faces=<?php echo $this->_tpl_vars['plugin']['faces']; ?>
&amp;width=<?php echo $this->_tpl_vars['plugin']['width']; ?>
&amp;height=<?php echo $this->_tpl_vars['plugin']['height']; ?>
&amp;action=<?php echo $this->_tpl_vars['plugin']['action']; ?>
<?php if ($this->_tpl_vars['plugin']['font']): ?>&amp;font=<?php echo $this->_tpl_vars['plugin']['font']; ?>
<?php endif; ?>&amp;colorscheme=<?php echo $this->_tpl_vars['plugin']['colorscheme']; ?>
<?php if ($this->_tpl_vars['plugin']['ref']): ?>&amp;ref=<?php echo $this->_tpl_vars['plugin']['ref']; ?>
<?php endif; ?>" scrolling="no" frameborder="0" style="border: none; overflow: hidden; width: <?php echo $this->_tpl_vars['plugin']['width']; ?>
px; height: <?php echo $this->_tpl_vars['plugin']['height']; ?>
px; vertical-align: middle"></iframe>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo Zikula_View_Resource::block_nocache($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); if ($this->caching && !$this->_cache_including): echo '{/nocache:6099a40aa5084d8a5f79fbbfb26ba351#0}'; endif;?>