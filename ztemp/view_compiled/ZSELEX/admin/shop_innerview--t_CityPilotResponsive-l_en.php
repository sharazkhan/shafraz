<?php /* Smarty version 2.6.28, created on 2017-12-10 15:14:23
         compiled from admin/shop_innerview.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'shopheader', 'admin/shop_innerview.tpl', 1, false),array('function', 'ajaxheader', 'admin/shop_innerview.tpl', 2, false),array('function', 'icon', 'admin/shop_innerview.tpl', 6, false),array('function', 'gt', 'admin/shop_innerview.tpl', 7, false),array('function', 'homepage', 'admin/shop_innerview.tpl', 21, false),array('function', 'modurl', 'admin/shop_innerview.tpl', 26, false),array('function', 'adminfooter', 'admin/shop_innerview.tpl', 54, false),array('modifier', 'cleantext', 'admin/shop_innerview.tpl', 36, false),array('modifier', 'upper', 'admin/shop_innerview.tpl', 36, false),)), $this); ?>
  <?php echo smarty_function_shopheader(array(), $this);?>

  <?php echo smarty_function_ajaxheader(array('imageviewer' => 'true'), $this);?>


 
<div class="z-admin-content-pagetitle">
    <?php echo smarty_function_icon(array('type' => 'view','size' => 'small'), $this);?>

    <h3><?php echo smarty_function_gt(array('text' => 'Shop Page'), $this);?>
</h3>
</div>
<a href="<?php echo smarty_function_homepage(array(), $this);?>
"><?php echo smarty_function_gt(array('text' => 'HOME'), $this);?>
</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<?php if ($this->_tpl_vars['notupdate_recent'] == 1): ?>
    <span>
   <?php echo smarty_function_gt(array('text' => 'You have not updated in recent time'), $this);?>
&nbsp;&nbsp;&nbsp;<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'shopinnerview','shop_id' => $_REQUEST['shop_id'],'op' => 'cl'), $this);?>
"><?php echo smarty_function_gt(array('text' => 'clear this'), $this);?>
</a>
    </span>
<?php endif; ?> 

<div style='border:solid 1px #CCC; padding-left:15px; padding-top:15px; padding-bottom:5px'> 

<table>   
<tr><td>
    <b><?php echo smarty_function_gt(array('text' => 'Shop Name'), $this);?>
</b>:</td><td> 
    <?php if ($this->_tpl_vars['perm'] == 1): ?>
    <a href='<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shop','shop_id' => $_REQUEST['shop_id']), $this);?>
'><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['shop_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)))) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
 </a>
    <?php else: ?>
    <?php echo $this->_tpl_vars['miniShopLinkStrt']; ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['item']['shop_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)))) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
<?php echo $this->_tpl_vars['miniShopLinkEnd']; ?>
   
    <?php endif; ?>    
</td></tr>
<tr><td>
<b><?php echo smarty_function_gt(array('text' => 'Address'), $this);?>
</b>:</td><td> <?php echo $this->_tpl_vars['item']['address']; ?>
</td></tr>
<tr><td>
<b><?php echo smarty_function_gt(array('text' => 'Telephone'), $this);?>
</b>:</td><td> <?php echo $this->_tpl_vars['item']['telephone']; ?>
</td></tr>
<tr><td>
<b><?php echo smarty_function_gt(array('text' => 'Fax'), $this);?>
</b>:</td><td> <?php echo $this->_tpl_vars['item']['fax']; ?>
</td></tr>
<tr><td>
<b><?php echo smarty_function_gt(array('text' => 'Email'), $this);?>
</b>:</td><td> <?php echo $this->_tpl_vars['item']['email']; ?>
</td></tr>

<tr><td><b><?php echo smarty_function_gt(array('text' => 'Diskquota'), $this);?>
</b>:</td><td> <?php echo $this->_tpl_vars['ownerfoldersize']; ?>
 <?php echo smarty_function_gt(array('text' => 'used of'), $this);?>
 <?php echo $this->_tpl_vars['ownerfolderquota']; ?>
</td></tr>
</table>
</div>

 <?php echo smarty_function_adminfooter(array(), $this);?>
