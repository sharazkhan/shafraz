<?php /* Smarty version 2.6.28, created on 2017-10-29 15:51:40
         compiled from errors_user_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'errors_user_main.tpl', 2, false),array('modifier', 'safehtml', 'errors_user_main.tpl', 5, false),)), $this); ?>
<div class="z-fullerror">
    <h2><?php echo smarty_function_gt(array('text' => "Error on %s",'tag1' => $this->_tpl_vars['modvars']['ZConfig']['sitename']), $this);?>
</h2>
    <ul>
        <?php $_from = $this->_tpl_vars['messages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['message']):
?>
        <li><?php echo ((is_array($_tmp=$this->_tpl_vars['message'])) ? $this->_run_mod_handler('safehtml', true, $_tmp) : smarty_modifier_safehtml($_tmp)); ?>
</li>
        <?php endforeach; endif; unset($_from); ?>
    </ul>
    <?php if ($this->_tpl_vars['trace']): ?>
    <ul>
        <h3><?php echo smarty_function_gt(array('text' => 'Exception Trace'), $this);?>
</h3>
        <?php $_from = $this->_tpl_vars['trace']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
            <li><?php echo ((is_array($_tmp=$this->_tpl_vars['t'])) ? $this->_run_mod_handler('safehtml', true, $_tmp) : smarty_modifier_safehtml($_tmp)); ?>
</li>
        <?php endforeach; endif; unset($_from); ?>
    </ul>
    <?php endif; ?>
    <p><a href="javascript:history.back(-1)"><?php echo smarty_function_gt(array('text' => 'Go back to previous page'), $this);?>
</a></p>
</div>