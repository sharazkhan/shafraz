<?php /* Smarty version 2.6.28, created on 2018-02-17 19:33:09
         compiled from ajax/productadhigh.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'setdiscount', 'ajax/productadhigh.tpl', 4, false),array('function', 'modurl', 'ajax/productadhigh.tpl', 5, false),array('function', 'imageproportional', 'ajax/productadhigh.tpl', 14, false),array('function', 'shorttext', 'ajax/productadhigh.tpl', 21, false),array('function', 'gt', 'ajax/productadhigh.tpl', 26, false),array('function', 'displayprice', 'ajax/productadhigh.tpl', 31, false),array('modifier', 'replace', 'ajax/productadhigh.tpl', 15, false),array('modifier', 'cleantext', 'ajax/productadhigh.tpl', 21, false),)), $this); ?>
<div class="row">
    <?php $_from = $this->_tpl_vars['highad']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
    <?php if ($this->_tpl_vars['product']['SHOPTYPE'] == 'iSHOP'): ?>
    <?php echo smarty_function_setdiscount(array('value' => $this->_tpl_vars['product']['discount'],'orig_price' => $this->_tpl_vars['product']['original_price'],'product_id' => $this->_tpl_vars['product']['product_id']), $this);?>

    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['product']['products_id']), $this);?>
">
       <div class="col-sm-6 special-product hover-border">
            <div class="thumbnail">
                <?php if ($this->_tpl_vars['is_discount']): ?>
                    <span class="offer-pop">-<?php echo $this->_tpl_vars['dicount_value']; ?>
</span>
                <?php endif; ?>
                <?php $this->assign('imagepath', "zselexdata/".($this->_tpl_vars['product']['SHOPID'])."/products/thumb/".($this->_tpl_vars['product']['products_image'])); ?>
                <div class="pro-image">
                    <?php if (file_exists ( $this->_tpl_vars['imagepath'] ) && $this->_tpl_vars['product']['products_image'] != ''): ?> 
                    <?php echo smarty_function_imageproportional(array('image' => $this->_tpl_vars['product']['products_image'],'path' => ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['product']['SHOPID'])."/products/thumb",'height' => '145','width' => '170'), $this);?>
    
                    <img <?php echo $this->_tpl_vars['imagedimensions']; ?>
 class="lazy"  src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['product']['SHOPID']; ?>
/products/thumb/<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['products_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '%20') : smarty_modifier_replace($_tmp, ' ', '%20')); ?>
" alt="<?php echo $this->_tpl_vars['product']['products_name']; ?>
">
                    <?php endif; ?>
                </div>
                <div class="product-caption">
                    <h3>
                        <?php if ($this->_tpl_vars['product']['products_name'] != ''): ?>
                        <?php echo smarty_function_shorttext(array('text' => ((is_array($_tmp=$this->_tpl_vars['product']['products_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)),'len' => 35), $this);?>
 
                        <?php endif; ?>
                    </h3>
                    <div class="pro-sub-row clearfix">
                        <div class="product-sub-text">
                            <span><?php echo smarty_function_gt(array('text' => 'Shop'), $this);?>
:</span>
                            <span class="sub-name"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['shopName'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</span>
                        </div>
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

        </div>
    </a>

    <?php else: ?>  
    <a href="http://<?php echo $this->_tpl_vars['product']['domainname']; ?>
/index.php?main_page=product_info&products_id=<?php echo $this->_tpl_vars['product']['products_id']; ?>
">
        <div class="col-sm-6 special-product hover-border">

            <div class="thumbnail">
                <?php $this->assign('imagepath', "zselexdata/".($this->_tpl_vars['product']['SHOPID'])."/products/thumb/".($this->_tpl_vars['product']['products_image'])); ?>
                <div class="pro-image">
                    <?php if ($this->_tpl_vars['product']['file_exists1']): ?>
                    <img class="lazy" <?php if (! empty ( $this->_tpl_vars['product']['W'] )): ?> style="width:<?php echo $this->_tpl_vars['product']['W']; ?>
px;height:<?php echo $this->_tpl_vars['product']['H']; ?>
px" <?php else: ?> width="170px" <?php endif; ?> src="<?php echo $this->_tpl_vars['baseurl']; ?>
modules/ZSELEX/images/grey_small.gif" data-original="http://<?php echo $this->_tpl_vars['product']['domainname']; ?>
/images/<?php echo $this->_tpl_vars['product']['products_image']; ?>
" >
                         <?php endif; ?>
                </div>
                <div class="product-caption">
                    <h3>
                        <?php if ($this->_tpl_vars['product']['manufacturers_name'] != ''): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['manufacturers_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
<?php endif; ?>
                    </h3>
                    <div class="pro-sub-row clearfix">
                        <div class="product-sub-text">
                            <span><?php echo smarty_function_gt(array('text' => 'Shop'), $this);?>
:</span>
                            <span class="sub-name"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['shopName'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</span>
                        </div>
                        <div class="product-amount">
                            <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['product']['products_price']), $this);?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </a>
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>

</div>