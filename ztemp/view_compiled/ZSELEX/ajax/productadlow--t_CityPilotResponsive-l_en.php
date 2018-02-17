<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:09
         compiled from ajax/productadlow.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'ajax/productadlow.tpl', 2, false),array('function', 'setdiscount', 'ajax/productadlow.tpl', 5, false),array('function', 'modurl', 'ajax/productadlow.tpl', 6, false),array('function', 'imageproportional', 'ajax/productadlow.tpl', 16, false),array('function', 'shorttext', 'ajax/productadlow.tpl', 23, false),array('function', 'displayprice', 'ajax/productadlow.tpl', 28, false),array('modifier', 'replace', 'ajax/productadlow.tpl', 17, false),array('modifier', 'cleantext', 'ajax/productadlow.tpl', 23, false),)), $this); ?>
 
<?php echo smarty_function_counter(array('assign' => 'idx','start' => 0,'print' => 0), $this);?>

<?php $_from = $this->_tpl_vars['lowad']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
<?php echo smarty_function_counter(array(), $this);?>

<?php echo smarty_function_setdiscount(array('value' => $this->_tpl_vars['product']['discount'],'orig_price' => $this->_tpl_vars['product']['original_price'],'product_id' => $this->_tpl_vars['product']['product_id']), $this);?>

<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['product']['products_id']), $this);?>
">
   <div class="col-sm-3 col-xs-6 special-sub-product hover-border nopadding">
          <!--<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['product']['products_id']), $this);?>
">-->
             <?php if ($this->_tpl_vars['is_discount']): ?>
       <span class="offer-pop">-<?php echo $this->_tpl_vars['dicount_value']; ?>
</span>
        <?php endif; ?>
            <div class="thumbnail">
                <div class="pro-image">
                    <?php $this->assign('imagepath', "zselexdata/".($this->_tpl_vars['product']['SHOPID'])."/products/thumb/".($this->_tpl_vars['product']['products_image'])); ?>
                    <?php if (file_exists ( $this->_tpl_vars['imagepath'] ) && $this->_tpl_vars['product']['products_image'] != ''): ?> 
                    <?php echo smarty_function_imageproportional(array('image' => $this->_tpl_vars['product']['products_image'],'path' => ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['product']['SHOPID'])."/products/thumb",'height' => '90','width' => '128'), $this);?>

                    <img <?php echo $this->_tpl_vars['imagedimensions']; ?>
 class="lazy"   src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['product']['SHOPID']; ?>
/products/thumb/<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['products_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
" alt="<?php echo $this->_tpl_vars['product']['products_name']; ?>
">

                    <?php endif; ?>
                </div>
                <div class="product-caption">
                    <h3>
                        <?php if ($this->_tpl_vars['product']['products_name'] != ''): ?><?php echo smarty_function_shorttext(array('text' => ((is_array($_tmp=$this->_tpl_vars['product']['products_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)),'len' => 25), $this);?>
<?php endif; ?>
                    </h3>
                    <div class="pro-sub-row clearfix">
                        <div class="product-amount">
                            <?php if ($this->_tpl_vars['is_discount']): ?>
                            <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['dicount_price']), $this);?>
 DKK
                            <?php else: ?>
                            <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['product']['prd_price']), $this);?>
 DKK 
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <!--</a>-->
    </div>
</a>
<?php endforeach; endif; unset($_from); ?>