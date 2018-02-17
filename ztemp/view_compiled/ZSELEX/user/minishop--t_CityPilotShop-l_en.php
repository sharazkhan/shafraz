<?php /* Smarty version 2.6.28, created on 2017-10-10 23:48:16
         compiled from user/minishop.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'user/minishop.tpl', 1, false),array('function', 'modurl', 'user/minishop.tpl', 6, false),array('function', 'gt', 'user/minishop.tpl', 11, false),array('function', 'pager', 'user/minishop.tpl', 46, false),array('function', 'setdiscount', 'user/minishop.tpl', 55, false),array('function', 'imageproportional', 'user/minishop.tpl', 65, false),array('function', 'shorttext', 'user/minishop.tpl', 72, false),array('function', 'displayprice', 'user/minishop.tpl', 74, false),array('function', 'product_option_exist', 'user/minishop.tpl', 86, false),)), $this); ?>
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => ($this->_tpl_vars['themepath'])."/javascript/product_user.js?v=1.1"), $this);?>
 
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => ($this->_tpl_vars['themepath'])."/javascript/minishop.js"), $this);?>

<div class="product-list-head clearfix">
    <div class="col-md-7 col-sm-6 product-dropdown-select">
        <div class="dropdown-sub">
            <form  class="z-form" id="cat_filter" id="cat_filter"  action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shop','shop_id' => $_REQUEST['shop_id']), $this);?>
" method="post" enctype="application/x-www-form-urlencoded">
                   <input type="hidden" name="startnum" value="<?php echo $this->_tpl_vars['startnum']; ?>
" />
                <input type="hidden" name="submit_category" value="1" />
                <input type="hidden" name="mnfrIds" value="<?php echo $this->_tpl_vars['manfIds']; ?>
" />

                <select name='prod_category[]' id="prod_category" onchange="document.forms['cat_filter'].submit();" data-placeholder="<?php echo smarty_function_gt(array('text' => 'Select Category'), $this);?>
" multiple class="chosen-select form-control mcategory" tabindex="1">
                    <option value=""></option>
                    <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
                    <option value="<?php echo $this->_tpl_vars['item']['prd_cat_id']; ?>
"  <?php $_from = $this->_tpl_vars['prod_catIdsArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['itm']):
?> <?php if ($this->_tpl_vars['item']['prd_cat_id'] == $this->_tpl_vars['itm']): ?> selected="selected" <?php endif; ?> <?php endforeach; endif; unset($_from); ?> > <?php echo $this->_tpl_vars['item']['prd_cat_name']; ?>
 </option>
                    <?php endforeach; endif; unset($_from); ?>
                </select>
            </form>
        </div>
        <div class="dropdown-sub">
            <form   class="z-form" id="mnfr_filter"  action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shop','shop_id' => $_REQUEST['shop_id']), $this);?>
" method="post" enctype="application/x-www-form-urlencoded">
                    <input type="hidden" name="startnum" value="<?php echo $this->_tpl_vars['startnum']; ?>
" />
                <input type="hidden" name="prod_categorys" value="<?php echo $this->_tpl_vars['prod_catIds']; ?>
" />
                <input type="hidden" name="submit_mnfr" value="1" />
                <select name='prod_mnfr[]'  id="prod_mnfr" onchange="document.forms['mnfr_filter'].submit();" data-placeholder="<?php echo smarty_function_gt(array('text' => 'Select Manufacturer'), $this);?>
" multiple class="chosen-select form-control mmanuf" tabindex="1">
                    <option value=""></option>
                    <?php $_from = $this->_tpl_vars['manufacturers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['manufacturer']):
?>
                    <option value="<?php echo $this->_tpl_vars['manufacturer']['manufacturer_id']; ?>
" <?php $_from = $this->_tpl_vars['manfIdsArr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['itm1']):
?> <?php if ($this->_tpl_vars['manufacturer']['manufacturer_id'] == $this->_tpl_vars['itm1']): ?> selected="selected" <?php endif; ?> <?php endforeach; endif; unset($_from); ?>   > <?php echo $this->_tpl_vars['manufacturer']['manufacturer_name']; ?>
 </option>
                    <?php endforeach; endif; unset($_from); ?>
                </select>
            </form>
        </div>
    </div>
    <div class="text-right col-md-5 col-sm-6 product-pagination">
                <?php if ($this->_tpl_vars['total_count'] > 0): ?>
        <?php echo smarty_function_pager(array('rowcount' => $this->_tpl_vars['total_count'],'limit' => $this->_tpl_vars['itemsperpage'],'posvar' => 'startnum','maxpages' => '5'), $this);?>
 
        <?php endif; ?>
    </div>
</div>
    <?php if ($this->_tpl_vars['perm']): ?>
<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'products','shop_id' => $_REQUEST['shop_id']), $this);?>
" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i><?php echo smarty_function_gt(array('text' => 'Edit Products'), $this);?>
</a>
<?php endif; ?>
<div class="clearfix product-list-wrapper">
    <?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
    <?php echo smarty_function_setdiscount(array('value' => $this->_tpl_vars['item']['discount'],'orig_price' => $this->_tpl_vars['item']['original_price'],'product_id' => $this->_tpl_vars['item']['product_id']), $this);?>

    <div class="col-sm-4 col-xs-6 btm-product-list hover-border">
        <div class="thumbnail text-center">
            <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['item']['product_id']), $this);?>
">
                <?php $this->assign('imagepath', "zselexdata/".($this->_tpl_vars['shop_id'])."/products/thumb/".($this->_tpl_vars['item']['prd_image'])); ?>
                 <?php if ($this->_tpl_vars['is_discount']): ?>
                <span class="offer-pop">-<?php echo $this->_tpl_vars['dicount_value']; ?>
</span>
                 <?php endif; ?>
                <div class="pro-image">
                    <?php if (file_exists ( $this->_tpl_vars['imagepath'] ) && $this->_tpl_vars['item']['prd_image'] != ''): ?> 
                    <?php echo smarty_function_imageproportional(array('image' => $this->_tpl_vars['item']['prd_image'],'path' => ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['shop_id'])."/products/medium",'height' => '300','width' => '410'), $this);?>

                    <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/products/medium/<?php echo $this->_tpl_vars['item']['prd_image']; ?>
" class="img-responsive" alt="" <?php echo $this->_tpl_vars['imageProperty']; ?>
>
                    <?php else: ?>
                    <img class="img-responsive"  src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/no-image.jpg"  width="150" height="150"/>
                    <?php endif; ?>
                </div>
                <div class="btm-product-name clearfix">
                    <h4><?php echo smarty_function_shorttext(array('text' => $this->_tpl_vars['item']['product_name'],'len' => 22), $this);?>
</h4>
                     <?php if ($this->_tpl_vars['is_discount']): ?>
                    <h5><del><?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['item']['original_price']), $this);?>
 DKK</del></h5>
                     <?php endif; ?>
                    <h5><b>
                            <?php if ($this->_tpl_vars['is_discount']): ?>
                            <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['dicount_price']), $this);?>
 DKK
                            <?php else: ?>
                            <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['item']['prd_price']), $this);?>
 DKK
                            <?php endif; ?>
                        </b></h5>
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
                <?php if ($this->_tpl_vars['item']['enable_question'] < 1): ?>
                <a id="buytxt<?php echo $this->_tpl_vars['item']['product_id']; ?>
" href="#" onClick="addToCartOptions('<?php echo $this->_tpl_vars['item']['product_id']; ?>
', '<?php echo $_REQUEST['shop_id']; ?>
', '<?php echo $this->_tpl_vars['loggedIn']; ?>
');return false;" class="btn buy-btn"><?php echo smarty_function_gt(array('text' => 'BUY'), $this);?>
</a>
                <?php else: ?>
                <a id="buytxt<?php echo $this->_tpl_vars['item']['product_id']; ?>
" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['item']['product_id']), $this);?>
" class="btn buy-btn"><?php echo smarty_function_gt(array('text' => 'BUY'), $this);?>
</a>  
                <?php endif; ?>   
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
                <?php if ($this->_tpl_vars['item']['prd_price'] > 0): ?>
                <a id="buytxt<?php echo $this->_tpl_vars['item']['product_id']; ?>
" href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['item']['product_id']), $this);?>
" class="btn buy-btn"><?php echo smarty_function_gt(array('text' => 'BUY'), $this);?>
</a>  
                <?php endif; ?>
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
<div class="product-list-footer clearfix">
    <div class="col-sm-12  product-pagination">
        
        <?php if ($this->_tpl_vars['total_count'] > 0): ?>
        <?php echo smarty_function_pager(array('rowcount' => $this->_tpl_vars['total_count'],'limit' => $this->_tpl_vars['itemsperpage'],'posvar' => 'startnum','maxpages' => '5'), $this);?>

        <?php endif; ?>    


    </div>

</div>