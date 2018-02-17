<?php /* Smarty version 2.6.28, created on 2018-02-03 08:18:00
         compiled from users_user_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'users_user_main.tpl', 1, false),array('function', 'alteraccountmenu', 'users_user_main.tpl', 3, false),array('function', 'submitarticle', 'users_user_main.tpl', 6, false),array('function', 'img', 'users_user_main.tpl', 21, false),array('modifier', 'safetext', 'users_user_main.tpl', 21, false),)), $this); ?>
<?php echo smarty_function_gt(array('text' => 'My account','assign' => 'templatetitle'), $this);?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'users_user_menu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo smarty_function_alteraccountmenu(array('accountlinks' => $this->_tpl_vars['accountLinks']), $this);?>


<?php $_from = $this->_tpl_vars['new_accountlinks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['accountLink']):
?>
     <?php echo smarty_function_submitarticle(array('link' => $this->_tpl_vars['accountLink']['url']), $this);?>

   
<div class="z-accountlink">
    <?php if ($this->_tpl_vars['modvars']['Users']['accountdisplaygraphics'] == 1): ?>
        <?php if (isset ( $this->_tpl_vars['accountLink']['set'] ) && ! empty ( $this->_tpl_vars['accountLink']['set'] )): ?>
            <?php $this->assign('iconset', $this->_tpl_vars['accountLink']['set']); ?>
    <?php else: ?>
            <?php $this->assign('iconset', null); ?>
    <?php endif; ?>
    
    <?php if ($this->_tpl_vars['perm'] != 1): ?>
        <?php if ($this->_tpl_vars['accountLink']['url'] == "news/newitem/" || $this->_tpl_vars['accountLink']['url'] == "index.php?module=news&type=user&func=newitem"): ?>
        <?php else: ?>
            <?php echo $this->_tpl_vars['iconset']; ?>
<br>
         <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['accountLink']['url'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
"><?php echo smarty_function_img(array('src' => $this->_tpl_vars['accountLink']['icon'],'modname' => $this->_tpl_vars['accountLink']['module'],'set' => $this->_tpl_vars['iconset']), $this);?>
</a>
        <?php endif; ?>
       
    <?php else: ?>
        <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['accountLink']['url'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
"><?php echo smarty_function_img(array('src' => $this->_tpl_vars['accountLink']['icon'],'modname' => $this->_tpl_vars['accountLink']['module'],'set' => $this->_tpl_vars['iconset']), $this);?>
</a>
    <?php endif; ?>
         
        <br />
    <?php endif; ?>
     
     <?php if ($this->_tpl_vars['perm'] != 1): ?>
        <?php if ($this->_tpl_vars['accountLink']['url'] == "news/newitem/" || $this->_tpl_vars['accountLink']['url'] == "index.php?module=news&type=user&func=newitem"): ?>
        <?php else: ?>
        <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['accountLink']['url'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['accountLink']['title'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</a>
        <?php endif; ?>
     <?php else: ?>
    <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['accountLink']['url'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['accountLink']['title'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)); ?>
</a>
      <?php endif; ?>   

</div>
<?php endforeach; endif; unset($_from); ?>
<br style="clear: left" />