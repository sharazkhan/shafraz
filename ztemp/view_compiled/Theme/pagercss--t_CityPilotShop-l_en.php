<?php /* Smarty version 2.6.28, created on 2018-02-17 19:13:13
         compiled from pagercss.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'pagercss.tpl', 2, false),array('function', 'gt', 'pagercss.tpl', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['pagerPluginArray']['includeStylesheet']): ?>
<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "system/Theme/style/pagercss.css"), $this);?>

<?php endif; ?>
<?php $this->assign('separator', "&ndash;"); ?>

<div class="<?php echo $this->_tpl_vars['pagerPluginArray']['class']; ?>
 z-pagercss">
    <?php if ($this->_tpl_vars['pagerPluginArray']['currentPage'] > 1): ?>
    <a href="<?php echo $this->_tpl_vars['pagerPluginArray']['firstUrl']; ?>
" title="<?php echo smarty_function_gt(array('text' => 'First page'), $this);?>
" class="z-pagercss-first">&laquo;</a>
    <a href="<?php echo $this->_tpl_vars['pagerPluginArray']['prevUrl']; ?>
" title="<?php echo smarty_function_gt(array('text' => 'Previous page'), $this);?>
" class="z-pagercss-prev">&lsaquo;</a>
    <?php else: ?>
    <span class="z-pagercss-first" title="<?php echo smarty_function_gt(array('text' => 'First page'), $this);?>
">&laquo;</span>
    <span class="z-pagercss-prev" title="<?php echo smarty_function_gt(array('text' => 'Previous page'), $this);?>
">&lsaquo;</span>
    <?php endif; ?>

    <?php $_from = $this->_tpl_vars['pagerPluginArray']['pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['pages'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['pages']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['currentItem'] => $this->_tpl_vars['currentPage']):
        $this->_foreach['pages']['iteration']++;
?>
    <?php if ($this->_tpl_vars['currentPage']['isCurrentPage']): ?>
    <span class="z-pagercss-current"><?php echo $this->_tpl_vars['currentItem']; ?>
</span>
    <?php else: ?>
    <a href="<?php echo $this->_tpl_vars['currentPage']['url']; ?>
" class="z-pagercss-item"><?php echo $this->_tpl_vars['currentItem']; ?>
</a>
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>

    <?php if ($this->_tpl_vars['pagerPluginArray']['currentPage'] < $this->_tpl_vars['pagerPluginArray']['countPages']): ?>
    <a href="<?php echo $this->_tpl_vars['pagerPluginArray']['nextUrl']; ?>
" title="<?php echo smarty_function_gt(array('text' => 'Next page'), $this);?>
" class="z-pagercss-next">&rsaquo;</a>
    <a href="<?php echo $this->_tpl_vars['pagerPluginArray']['lastUrl']; ?>
" title="<?php echo smarty_function_gt(array('text' => 'Last page'), $this);?>
" class="z-pagercss-last">&raquo;</a>
    <?php else: ?>
    <span class="z-pagercss-next" title="<?php echo smarty_function_gt(array('text' => 'Next page'), $this);?>
">&rsaquo;</span>
    <span class="z-pagercss-last" title="<?php echo smarty_function_gt(array('text' => 'Last page'), $this);?>
">&raquo;</span>
    <?php endif; ?>
</div>