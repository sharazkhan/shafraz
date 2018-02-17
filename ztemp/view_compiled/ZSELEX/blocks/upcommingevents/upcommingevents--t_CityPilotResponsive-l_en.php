<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:09
         compiled from blocks/upcommingevents/upcommingevents.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'blocks/upcommingevents/upcommingevents.tpl', 12, false),)), $this); ?>




<input type="hidden" id="upcommingeventlimit" value="<?php echo $this->_tpl_vars['vars']['upcommingeventlimit']; ?>
">

<div class="col-md-4 contents-right">
  <h2><?php echo smarty_function_gt(array('text' => 'Events'), $this);?>
</h2>
<div id="upcommingevents">
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ajax/upcommingevents.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
  </div>
