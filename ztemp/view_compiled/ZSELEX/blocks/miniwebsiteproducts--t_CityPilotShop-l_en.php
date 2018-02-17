<?php /* Smarty version 2.6.28, created on 2017-10-29 15:00:53
         compiled from blocks/miniwebsiteproducts.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'blocks/miniwebsiteproducts.tpl', 1, false),array('function', 'modurl', 'blocks/miniwebsiteproducts.tpl', 5, false),array('function', 'gt', 'blocks/miniwebsiteproducts.tpl', 5, false),array('function', 'setdiscount', 'blocks/miniwebsiteproducts.tpl', 18, false),array('function', 'imageproportional', 'blocks/miniwebsiteproducts.tpl', 25, false),array('function', 'shorttext', 'blocks/miniwebsiteproducts.tpl', 32, false),array('function', 'displayprice', 'blocks/miniwebsiteproducts.tpl', 35, false),array('function', 'product_option_exist', 'blocks/miniwebsiteproducts.tpl', 50, false),)), $this); ?>
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => ($this->_tpl_vars['themepath'])."/javascript/product_user.js?v=1.1"), $this);?>
 


<?php if ($this->_tpl_vars['perm']): ?>
<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'products','shop_id' => $_REQUEST['shop_id']), $this);?>
" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i><?php echo smarty_function_gt(array('text' => 'Edit Products'), $this);?>
</a>
<?php endif; ?>

<?php if ($this->_tpl_vars['productCount'] > 0): ?>
<div class="products-wrap clearfix col-sm-12 product-list">
    <div class="product-head clearfix">
        <h3 class="pull-left">Products</h3>
        <?php if ($this->_tpl_vars['linkToShop']): ?>
        <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shop','shop_id' => $this->_tpl_vars['shop_id']), $this);?>
" class="see-all pull-right"><?php echo smarty_function_gt(array('text' => 'See All'), $this);?>
  <i class="fa fa-caret-right"></i></a>
        <?php endif; ?>
    </div>
    <div class="row">
        <?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
        <?php echo smarty_function_setdiscount(array('value' => $this->_tpl_vars['item']['discount'],'orig_price' => $this->_tpl_vars['item']['original_price'],'product_id' => $this->_tpl_vars['item']['product_id']), $this);?>

        <div class="col-sm-4 col-xs-6 btm-product-list hover-border">
            <div class="thumbnail text-center">
                <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['item']['product_id']), $this);?>
">
                    <?php $this->assign('imagepath', "zselexdata/".($this->_tpl_vars['shop_id'])."/products/thumb/".($this->_tpl_vars['item']['prd_image'])); ?>
                    <div class="pro-image">
                        <?php if (file_exists ( $this->_tpl_vars['imagepath'] ) && $this->_tpl_vars['item']['prd_image'] != ''): ?> 
                        <?php echo smarty_function_imageproportional(array('image' => $this->_tpl_vars['item']['prd_image'],'path' => ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['shop_id'])."/products/medium",'height' => '150','width' => '244'), $this);?>

                        <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/products/medium/<?php echo $this->_tpl_vars['item']['prd_image']; ?>
" <?php echo $this->_tpl_vars['imageProperty']; ?>
 class="img-responsive" alt="">
                        <?php else: ?>
                        <img class="img-responsive"  src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/no-image.jpg"  width="150" height="150"/>
                        <?php endif; ?>
                    </div>
                    <div class="btm-product-name clearfix">
                        <h4><?php echo smarty_function_shorttext(array('text' => $this->_tpl_vars['item']['product_name'],'len' => 22), $this);?>
</h4>
                        <?php if ($this->_tpl_vars['item']['prd_price'] > 0): ?>
                        <?php if ($this->_tpl_vars['is_discount']): ?>
                        <span style="text-decoration:line-through; color:red; font-size:14px"><span style="color:#717D82"><?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['item']['original_price']), $this);?>
 DKK</span></span><br>
                        <?php else: ?>
                        <span style="font-size:14px"><span style="color:#717D82">&nbsp;</span></span><br>
                        <?php endif; ?>
                        <h5>
                            <?php if ($this->_tpl_vars['is_discount']): ?>
                            <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['dicount_price']), $this);?>
 DKK
                            <?php else: ?>
                            <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['item']['prd_price']), $this);?>
 DKK
                            <?php endif; ?>
                        </h5>
                        <?php endif; ?>
                    </div>
                </a>
                <?php if (! $this->_tpl_vars['no_payment']): ?>
                <?php echo smarty_function_product_option_exist(array('product_id' => $this->_tpl_vars['item']['product_id']), $this);?>

                <?php if (! $this->_tpl_vars['optionExist']): ?> 
                <div class="Box BoxId<?php echo $this->_tpl_vars['item']['product_id']; ?>
" id="BoxId<?php echo $this->_tpl_vars['item']['product_id']; ?>
">
                    <?php if ($this->_tpl_vars['item']['prd_quantity'] > 0): ?>
                    <?php if ($this->_tpl_vars['item']['prd_price'] > 0): ?>
                    <a id="buytxt<?php echo $this->_tpl_vars['item']['product_id']; ?>
" href="#" class="btn buy-btn" onClick="addToCartOptions('<?php echo $this->_tpl_vars['item']['product_id']; ?>
', '<?php echo $_REQUEST['shop_id']; ?>
', '<?php echo $this->_tpl_vars['loggedIn']; ?>
');return false;"> <?php echo smarty_function_gt(array('text' => 'BUY'), $this);?>
</a>
                                       <?php endif; ?>
                    <?php else: ?>
                    <?php echo smarty_function_gt(array('text' => 'Out Of Stock!'), $this);?>
    
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="Box BoxId<?php echo $this->_tpl_vars['item']['product_id']; ?>
" id="BoxId<?php echo $this->_tpl_vars['item']['product_id']; ?>
">
                    <?php if ($this->_tpl_vars['optionQty'] > 0): ?>
                    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['item']['product_id']), $this);?>
" class="btn buy-btn"><?php echo smarty_function_gt(array('text' => 'BUY'), $this);?>
</a>
                    <?php else: ?>
                    <?php echo smarty_function_gt(array('text' => 'Out Of Stock!'), $this);?>

                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <span id="addloader<?php echo $this->_tpl_vars['item']['product_id']; ?>
"></span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; endif; unset($_from); ?>
    </div>
</div>
</div>
<?php endif; ?>