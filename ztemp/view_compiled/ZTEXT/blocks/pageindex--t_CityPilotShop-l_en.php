<?php /* Smarty version 2.6.28, created on 2018-02-17 19:13:13
         compiled from blocks/pageindex.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'modurl', 'blocks/pageindex.tpl', 3, false),array('function', 'gt', 'blocks/pageindex.tpl', 4, false),array('modifier', 'cleantext', 'blocks/pageindex.tpl', 13, false),)), $this); ?>
<?php if ($this->_tpl_vars['perm']): ?>
<div class="OrageEditSec EditEvent" style="">
    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZTEXT','type' => 'admin','func' => 'pages','shop_id' => $_REQUEST['shop_id']), $this);?>
">
        <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/OrageEdit.png"><?php echo smarty_function_gt(array('text' => 'Edit Pages'), $this);?>

    </a>
</div>
    <?php endif; ?>
    <?php if (! $this->_tpl_vars['disable_page_index']): ?>
<h3 class="EventName"><?php echo smarty_function_gt(array('text' => 'Page Index'), $this);?>
:</h3>
<div style="font-size: 14px">
<?php $_from = $this->_tpl_vars['pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
    <p>
        &nbsp;&nbsp;<a <?php if ($_REQUEST['text_id'] == $this->_tpl_vars['page']['text_id']): ?>style="color: #e65622;"<?php endif; ?> href="<?php echo smarty_function_modurl(array('modname' => 'ZTEXT','type' => 'user','func' => 'page','shop_id' => $_REQUEST['shop_id'],'text_id' => $this->_tpl_vars['page']['text_id']), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['page']['headertext'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</a>
    </p>
<?php endforeach; endif; unset($_from); ?>
</div>
<?php endif; ?>