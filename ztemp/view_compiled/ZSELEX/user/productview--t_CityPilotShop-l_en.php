<?php /* Smarty version 2.6.28, created on 2018-02-17 19:13:07
         compiled from user/productview.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'user/productview.tpl', 2, false),array('function', 'modurl', 'user/productview.tpl', 6, false),array('function', 'gt', 'user/productview.tpl', 7, false),array('function', 'setdiscount', 'user/productview.tpl', 11, false),array('function', 'displayprice', 'user/productview.tpl', 25, false),array('function', 'getParentProductToOptions', 'user/productview.tpl', 74, false),array('function', 'displayquantitydiscount', 'user/productview.tpl', 97, false),array('function', 'product_option_exist', 'user/productview.tpl', 101, false),array('function', 'fblikeservice', 'user/productview.tpl', 130, false),array('function', 'fbshare', 'user/productview.tpl', 132, false),array('modifier', 'cleantext', 'user/productview.tpl', 28, false),array('modifier', 'nl2br', 'user/productview.tpl', 28, false),)), $this); ?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => ($this->_tpl_vars['themepath'])."/javascript/product_user.js?v=1.1"), $this);?>
  


<?php if ($this->_tpl_vars['perm']): ?>
<a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'admin','func' => 'products','shop_id' => $_REQUEST['shop_id'],'product_id' => $this->_tpl_vars['product']['product_id'],'src' => 'detail'), $this);?>
" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>
    <?php echo smarty_function_gt(array('text' => 'Edit Product'), $this);?>

</a>
<?php endif; ?>

<?php echo smarty_function_setdiscount(array('value' => $this->_tpl_vars['product']['discount'],'orig_price' => $this->_tpl_vars['product']['original_price'],'product_id' => $this->_tpl_vars['product']['product_id']), $this);?>

<div class="clearfix product-details-wrapper">
    <div class="col-sm-6 btm-product-details hover-border">
        <div class="thumbnail text-center">
                            <div class="pro-image-product">
                    <a title="Image title here" href="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/products/fullsize/<?php echo $this->_tpl_vars['product']['prd_image']; ?>
" rel="gallery1" class="fancybox">
                    <?php if ($this->_tpl_vars['is_discount']): ?><span class="offer-pop">-<?php echo $this->_tpl_vars['dicount_value']; ?>
</span><?php endif; ?>
                        <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
/products/medium/<?php echo $this->_tpl_vars['product']['prd_image']; ?>
" class="img-responsive" alt="">
                    </a>
                </div>
                   </div>
    </div>
    <input type="hidden" id="origPrice" value="<?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['product']['prd_price']), $this);?>
">
    <input type="hidden" id="totPrice" value="">
    <div class="col-sm-6">
        <h2><b><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['product_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</b></h2>
        <?php if ($this->_tpl_vars['is_discount']): ?>
        <h5><del><?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['product']['original_price']), $this);?>
 DKK</del></h5>
        <?php endif; ?>
        <h3>
            <b>
                <?php if ($this->_tpl_vars['is_discount']): ?>
                <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['dicount_price']), $this);?>
 DKK
                <?php else: ?>
                <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['product']['prd_price']), $this);?>
 DKK 
                <?php endif; ?>
            </b>
        </h3>
        <p> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['prd_description'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</p>

        <div class="buy-section">
            <?php if ($this->_tpl_vars['option_count'] > 0): ?>  <p><b><?php echo smarty_function_gt(array('text' => 'Available Options'), $this);?>
:</b></p><?php endif; ?>
            <?php $_from = $this->_tpl_vars['product_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
            <span id="label-<?php echo $this->_tpl_vars['item']['product_to_options_id']; ?>
" class='option_name label-<?php echo $this->_tpl_vars['item']['product_to_options_id']; ?>
'><?php echo $this->_tpl_vars['item']['option_name']; ?>
 : </span><br>
            <?php if ($this->_tpl_vars['item']['option_type'] == 'radio'): ?>
            <div class="form-group">
                <?php $_from = $this->_tpl_vars['item']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?>
                <?php if ($this->_tpl_vars['value']['option_value_id'] > 0): ?>
                <input mytype="radio" price="<?php echo $this->_tpl_vars['value']['price']; ?>
" valueid="<?php echo $this->_tpl_vars['value']['product_to_options_value_id']; ?>
" id="test-<?php echo $this->_tpl_vars['item']['product_to_options_id']; ?>
" name="<?php echo $this->_tpl_vars['item']['option_name']; ?>
" class='options_select' name="product_options[<?php echo $this->_tpl_vars['item']['option_name']; ?>
][]" value="<?php echo $this->_tpl_vars['value']['option_value_id']; ?>
" <?php if ($this->_tpl_vars['item']['parent_option_id'] > 0): ?>linked="1"<?php else: ?>linked="0"<?php endif; ?> type="radio" onClick="changePrice('<?php echo $this->_tpl_vars['product']['product_id']; ?>
','<?php echo $this->_tpl_vars['item']['option_id']; ?>
',this.value,'<?php if ($this->_tpl_vars['item']['parent_option_id'] > 0): ?>1<?php else: ?>0<?php endif; ?>',0,'','<?php echo $this->_tpl_vars['item']['parent_option_id']; ?>
');"><?php echo $this->_tpl_vars['value']['option_value']; ?>
<?php if ($this->_tpl_vars['item']['parent_option_id'] < 1): ?>&nbsp;<?php if ($this->_tpl_vars['value']['price'] > 0 || $this->_tpl_vars['value']['price'] < 0): ?>(<?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['value']['price']), $this);?>
 DKK)<?php endif; ?><?php endif; ?>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?> 
            </div>
            <?php elseif ($this->_tpl_vars['item']['option_type'] == 'dropdown'): ?>
            <div class="form-group productview-dropdown">
                <select <?php if ($this->_tpl_vars['item']['parent_option_id'] > 0): ?>linked="1"<?php else: ?>linked="0"<?php endif; ?> mytype="dropdown" name="<?php echo $this->_tpl_vars['item']['option_name']; ?>
" class="chosen-product-select form-control options_select" id="test-<?php echo $this->_tpl_vars['item']['product_to_options_id']; ?>
" style="width:252px;" onChange="changePrice('<?php echo $this->_tpl_vars['product']['product_id']; ?>
','<?php echo $this->_tpl_vars['item']['option_id']; ?>
',this.value,'<?php if ($this->_tpl_vars['item']['parent_option_id'] > 0): ?>1<?php else: ?>0<?php endif; ?>',0,'','<?php echo $this->_tpl_vars['item']['parent_option_id']; ?>
');">
                    <option value=''><?php echo smarty_function_gt(array('text' => 'select'), $this);?>
</option>
                    <?php $_from = $this->_tpl_vars['item']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?>              
                    <option value="<?php echo $this->_tpl_vars['value']['option_value_id']; ?>
" price="<?php echo $this->_tpl_vars['value']['price']; ?>
" valueid="<?php echo $this->_tpl_vars['value']['product_to_options_value_id']; ?>
"><?php echo $this->_tpl_vars['value']['option_value']; ?>
<?php if ($this->_tpl_vars['item']['parent_option_id'] < 1): ?><?php if ($this->_tpl_vars['value']['price'] > 0 || $this->_tpl_vars['value']['price'] < 0): ?>&nbsp;(<?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['value']['price'],'pref' => true), $this);?>
 DKK)<?php endif; ?><?php endif; ?> </option>
                    <?php endforeach; endif; unset($_from); ?>  
                </select>
            </div>
            <?php elseif ($this->_tpl_vars['item']['option_type'] == 'checkbox'): ?>
            <div class="form-group">
                <?php $_from = $this->_tpl_vars['item']['values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['value']):
?>
                <input <?php if ($this->_tpl_vars['item']['parent_option_id'] > 0): ?>linked="1"<?php else: ?>linked="0"<?php endif; ?> mytype="checkbox" price="<?php echo $this->_tpl_vars['value']['price']; ?>
" valueid="<?php echo $this->_tpl_vars['value']['product_to_options_value_id']; ?>
"  id="test-<?php echo $this->_tpl_vars['item']['product_to_options_id']; ?>
" class='options_select' name="<?php echo $this->_tpl_vars['item']['option_name']; ?>
"  value="<?php echo $this->_tpl_vars['value']['option_value_id']; ?>
" type="checkbox" onClick="changePrice('<?php echo $this->_tpl_vars['product']['product_id']; ?>
','<?php echo $this->_tpl_vars['item']['option_id']; ?>
',this.value,'<?php if ($this->_tpl_vars['item']['parent_option_id'] > 0): ?>1<?php else: ?>0<?php endif; ?>',0, 'checkbox' , '<?php echo $this->_tpl_vars['item']['parent_option_id']; ?>
');"><?php echo $this->_tpl_vars['value']['option_value']; ?>
&nbsp;<?php if ($this->_tpl_vars['value']['price'] > 0 || $this->_tpl_vars['value']['price'] < 0): ?>&nbsp;(<?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['value']['price']), $this);?>
 DKK)<?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>  
            </div>
            <?php endif; ?>

            <?php if ($this->_tpl_vars['item']['parent_option_id'] > 0): ?>

            <?php echo smarty_function_getParentProductToOptions(array('parent_option_id' => $this->_tpl_vars['item']['parent_option_id'],'product_id' => $this->_tpl_vars['product']['product_id']), $this);?>

            <input type="hidden" id="parentTypeId" value="test-<?php echo $this->_tpl_vars['parent_product_options']['0']['parent_option_value_id']; ?>
">
            <span id="label-<?php echo $this->_tpl_vars['parent_product_options']['0']['parent_option_value_id']; ?>
" class='option_name label-<?php echo $this->_tpl_vars['parent_product_options']['0']['parent_option_value_id']; ?>
'><?php echo $this->_tpl_vars['parent_product_options']['0']['option_name']; ?>
 : </span><br>

            <span class="parentDisplay">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ajax/showParentOptionValues.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </span>

            <div class='showPrice'></div>

            <?php endif; ?>


            <?php endforeach; endif; unset($_from); ?>   

            <?php if ($this->_tpl_vars['product']['enable_question'] > 0): ?>
            <input type="hidden" id="ques_validate" value="<?php echo $this->_tpl_vars['product']['validate_question']; ?>
">
            <div class="form-group">
                <div class="quesLabel"><?php echo $this->_tpl_vars['product']['prd_question']; ?>
</div>
                <div><input type="text" id="ques_ans" value=""></div>
            </div>
            <?php endif; ?>
            <br>
            <?php echo smarty_function_displayquantitydiscount(array('product_id' => $this->_tpl_vars['product']['product_id']), $this);?>


            <div class="form-group">
                <?php if (! $this->_tpl_vars['no_payment']): ?>
                    <?php echo smarty_function_product_option_exist(array('product_id' => $this->_tpl_vars['product']['product_id']), $this);?>

                    <?php if (! $this->_tpl_vars['optionExist']): ?> 
                        <?php if ($this->_tpl_vars['product']['prd_quantity'] > 0): ?>
                            <?php if ($this->_tpl_vars['product']['prd_price'] > 0): ?>
                            <input type="button" id="OrangeBtn" value="<?php echo smarty_function_gt(array('text' => 'Add to Cart'), $this);?>
" class="btn btn-primary btn-detail BoxId<?php echo $this->_tpl_vars['product']['product_id']; ?>
" onclick="addToCartOptions('<?php echo $this->_tpl_vars['product']['product_id']; ?>
','<?php echo $_REQUEST['shop_id']; ?>
','<?php echo $this->_tpl_vars['loggedIn']; ?>
',1);">
                            <?php endif; ?>
                        <?php else: ?>
                            <?php echo smarty_function_gt(array('text' => 'Out Of Stock!'), $this);?>

                        <?php endif; ?>    

                    <?php else: ?>
                        <?php if ($this->_tpl_vars['optionQty'] > 0): ?>
                            <?php if ($this->_tpl_vars['product']['prd_price'] > 0): ?>
                            <input type="button" id="OrangeBtn" value="<?php echo smarty_function_gt(array('text' => 'Add to Cart'), $this);?>
" class="btn btn-primary btn-detail BoxId<?php echo $this->_tpl_vars['product']['product_id']; ?>
" onclick="addToCartOptions('<?php echo $this->_tpl_vars['product']['product_id']; ?>
','<?php echo $_REQUEST['shop_id']; ?>
','<?php echo $this->_tpl_vars['loggedIn']; ?>
',1);">   
                            <?php endif; ?>
                        <?php else: ?> 
                             <?php echo smarty_function_gt(array('text' => ' Out Of Stock!'), $this);?>

                        <?php endif; ?>   


                    <?php endif; ?>   
                     <span id="addloader<?php echo $this->_tpl_vars['product']['product_id']; ?>
"></span> 

                <?php endif; ?>
            </div>

            <!-- social share  -->
              <?php $this->assign('prod_image', ($this->_tpl_vars['baseurl'])."zselexdata/".($this->_tpl_vars['shop_id'])."/products/medium/".($this->_tpl_vars['product']['prd_image'])); ?>
            <div class="social-share">
                         <span><?php echo smarty_function_fblikeservice(array('action' => 'like','url' => $this->_tpl_vars['product_link'],'width' => '500px','height' => '21px','layout' => 'horizontal','shop_id' => $this->_tpl_vars['product']['shop_id'],'addmetatags' => true,'metatitle' => $this->_tpl_vars['product']['product_name'],'metatype' => 'website','metaimage' => $this->_tpl_vars['prod_image'],'description' => $this->_tpl_vars['metaContent'],'faces' => true), $this);?>
</span>
           
               <span><?php echo smarty_function_fbshare(array('shop_id' => $this->_tpl_vars['product']['shop_id'],'url' => $this->_tpl_vars['product_link']), $this);?>
</span>
      
                 </div>
            <!-- social end -->
        </div>
    </div>
</div>