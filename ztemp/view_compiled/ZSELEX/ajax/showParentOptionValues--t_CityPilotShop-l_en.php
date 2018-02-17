<?php /* Smarty version 2.6.28, created on 2017-10-29 09:45:22
         compiled from ajax/showParentOptionValues.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'ajax/showParentOptionValues.tpl', 5, false),)), $this); ?>
 
<?php if ($this->_tpl_vars['parent_product_options']['0']['option_type'] == 'dropdown'): ?>
    <div class="form-group">
   <select linked="1" <?php if (! $this->_tpl_vars['enable']): ?>disabled<?php endif; ?> parent="1" mytype="dropdown" id="test-<?php echo $this->_tpl_vars['parent_product_options']['0']['parent_option_value_id']; ?>
" class='options_select parents' name="<?php echo $this->_tpl_vars['parent_product_options']['0']['option_name']; ?>
" onChange="changePrice('<?php echo $this->_tpl_vars['parent_product_options']['0']['product_id']; ?>
','<?php echo $this->_tpl_vars['parent_product_options']['0']['option_id']; ?>
',this.value ,0,1,'');">
   <option value=''><?php echo smarty_function_gt(array('text' => 'select'), $this);?>
</option>
    <?php $_from = $this->_tpl_vars['parent_product_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pitem']):
?>              
               <option value="<?php echo $this->_tpl_vars['pitem']['parent_option_value_id']; ?>
" valueid="<?php echo $this->_tpl_vars['pitem']['parent_option_value_id']; ?>
"><?php echo $this->_tpl_vars['pitem']['option_value']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>  
   </select>
   </div>

<?php elseif ($this->_tpl_vars['parent_product_options']['0']['option_type'] == 'radio'): ?>
        <div class="form-group">
     <?php $_from = $this->_tpl_vars['parent_product_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pitem']):
?>              
              
       <input linked="1" <?php if (! $this->_tpl_vars['enable']): ?>disabled<?php endif; ?> parent="1" mytype="radio" valueid="<?php echo $this->_tpl_vars['pitem']['parent_option_value_id']; ?>
" id="test-<?php echo $this->_tpl_vars['parent_product_options']['0']['parent_option_value_id']; ?>
" name="product_options[<?php echo $this->_tpl_vars['pitem']['option_name']; ?>
][]"  class='options_select parents'  value="<?php echo $this->_tpl_vars['pitem']['parent_option_value_id']; ?>
" type="radio" onClick="changePrice('<?php echo $this->_tpl_vars['pitem']['product_id']; ?>
','<?php echo $this->_tpl_vars['parent_product_options']['0']['option_id']; ?>
',this.value ,0,1,'');"><?php echo $this->_tpl_vars['pitem']['option_value']; ?>
        
     <?php endforeach; endif; unset($_from); ?>  
        </div>
<?php endif; ?>