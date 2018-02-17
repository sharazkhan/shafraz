<?php /* Smarty version 2.6.28, created on 2018-02-17 19:15:36
         compiled from user/cart.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'user/cart.tpl', 4, false),array('function', 'setsellername', 'user/cart.tpl', 9, false),array('function', 'modurl', 'user/cart.tpl', 34, false),array('function', 'displayoptions', 'user/cart.tpl', 50, false),array('function', 'displayprice', 'user/cart.tpl', 61, false),array('function', 'displayquantitydiscount', 'user/cart.tpl', 72, false),array('function', 'homepage', 'user/cart.tpl', 86, false),array('modifier', 'cleantext', 'user/cart.tpl', 45, false),array('modifier', 'safetext', 'user/cart.tpl', 56, false),)), $this); ?>
<div class="row">
    <div class="col-md-12">
       <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/cartmenu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <h2><?php echo smarty_function_gt(array('text' => 'Shopping Cart'), $this);?>
</h2>

        <!-- CART WRAP 01 -->
            <?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cart_shop_id'] => $this->_tpl_vars['v']):
?>
                 <?php $this->assign('outOfStock', 0); ?>
                 <?php echo smarty_function_setsellername(array('value' => $this->_tpl_vars['products'],'key' => $this->_tpl_vars['cart_shop_id']), $this);?>

                        <form class="shop-cart-list" name='updatecart<?php echo $this->_tpl_vars['k']; ?>
' action='index.php?module=zselex&type=user&func=updateUserCart&shop_id=<?php echo $this->_tpl_vars['cart_shop_id']; ?>
' method='post'>
            <div class="city-box">
                <span><?php echo smarty_function_gt(array('text' => 'Shop'), $this);?>
  :</span><a href="#"><?php echo $this->_tpl_vars['shopname']; ?>
</a>
            </div>

            <table id="cart" class="table table-hover table-condensed cart-table">
                <thead>
                    <tr>
                        <th style="width:42%"><?php echo smarty_function_gt(array('text' => 'Product'), $this);?>
</th>
                        <th style="width:16%"><?php echo smarty_function_gt(array('text' => 'Price'), $this);?>
</th>
                        <th style="width:16%"><?php echo smarty_function_gt(array('text' => 'Quantity'), $this);?>
</th>
                        <th style="width:22%"><?php echo smarty_function_gt(array('text' => 'Subtotal'), $this);?>
</th>
                        <th style="width:4%"></th>
                    </tr>
                </thead>
                <tbody>
                     <?php $_from = $this->_tpl_vars['products'][$this->_tpl_vars['cart_shop_id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k1'] => $this->_tpl_vars['v1']):
?>  
                    <tr>
                        <td class="product-td" data-th="Product">
                            <div class="clearfix">
                                <div class="col-sm-3">
                                    <div class="product-image-box">
                                         <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['v1']['product_id']), $this);?>
">
                                        <?php $this->assign('imagepath', "zselexdata/".($this->_tpl_vars['cart_shop_id'])."/products/thumb/".($this->_tpl_vars['v1']['prd_image'])); ?>
                                          <?php if (file_exists ( $this->_tpl_vars['imagepath'] ) && $this->_tpl_vars['v1']['prd_image'] != ''): ?> 
                                        <img src="zselexdata/<?php echo $this->_tpl_vars['cart_shop_id']; ?>
/products/thumb/<?php echo $this->_tpl_vars['v1']['prd_image']; ?>
" alt="<?php echo $this->_tpl_vars['v1']['product_name']; ?>
" class="img-responsive"/>
                                        <?php else: ?>
                                        <img alt="<?php echo $this->_tpl_vars['v1']['product_name']; ?>
"  src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/no-image.jpg"  width="100" class="img-responsive"/>
                                          <?php endif; ?> 
                                         </a>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <h4 class="product-name-head"><?php echo ((is_array($_tmp=$this->_tpl_vars['v1']['product_name'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
<?php if ($this->_tpl_vars['v1']['outofstock']): ?><font color="red">&nbsp;***</font><?php endif; ?></h4>
                                    <p><?php echo ((is_array($_tmp=$this->_tpl_vars['v1']['prd_description'])) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
</p>
                                                                         <?php if ($this->_tpl_vars['v1']['cart_content'] != ''): ?>
                                            
                                            <?php echo smarty_function_displayoptions(array('options' => $this->_tpl_vars['v1']['cart_content']), $this);?>

                                        <?php endif; ?>
                                        <?php if ($this->_tpl_vars['v1']['enable_question']): ?>
                                           
                                             <p><?php echo $this->_tpl_vars['v1']['prd_question']; ?>
</p>:
                                           
                                             <input autocomplete="off" name="prd_answer[<?php echo $this->_tpl_vars['v1']['cart_id']; ?>
+<?php echo $this->_tpl_vars['v1']['product_id']; ?>
]"  value='<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v1']['prd_answer'])) ? $this->_run_mod_handler('safetext', true, $_tmp) : smarty_modifier_safetext($_tmp)))) ? $this->_run_mod_handler('cleantext', true, $_tmp) : smarty_modifier_cleantext($_tmp)); ?>
'>
                                         <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="price-td" data-th="Price">DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['v1']['price']), $this);?>
</td>
                        <td class="quantity-td" data-th="Quantity">
                            <input type="number" name="quantity[<?php echo $this->_tpl_vars['v1']['cart_id']; ?>
]"  class="form-control text-center" value="<?php echo $this->_tpl_vars['v1']['quantity']; ?>
" autocomplete="off">
                        </td>
                        <td class="subtotal-td" data-th="Subtotal">DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['v1']['final_price']), $this);?>
</td>
                        <td class="actions" data-th="">
                            <a href="index.php?module=zselex&type=user&func=deleteUserCart&id=<?php echo $this->_tpl_vars['v1']['cart_id']; ?>
&shop_id=<?php echo $this->_tpl_vars['cart_shop_id']; ?>
">
                                <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
                            </a>
                        </td>
                    </tr>
                      <?php echo smarty_function_displayquantitydiscount(array('product_id' => $this->_tpl_vars['v1']['product_id'],'page' => 'cart'), $this);?>

                      <?php if ($this->_tpl_vars['v1']['outofstock']): ?>
                                    <?php $this->assign('outOfStock', 1); ?>
                                   <?php endif; ?>
                     <?php endforeach; endif; unset($_from); ?>
                    
                </tbody>
                <tfoot>
                    <tr class="visible-xs">
                        <td class="text-center total-td"><strong><?php echo smarty_function_gt(array('text' => 'Total'), $this);?>
 <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['GRANDSUM']), $this);?>
</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shop','shop_id' => $this->_tpl_vars['cart_shop_id']), $this);?>
" class="btn btn-warning cs-btn btn-info"><i class="fa fa-angle-left"></i> <?php echo smarty_function_gt(array('text' => 'Continue Shopping'), $this);?>
</a>
                            <a href="<?php if ($this->_tpl_vars['last_shop_id'] > 0): ?><?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','shop_id' => $this->_tpl_vars['last_shop_id']), $this);?>
<?php else: ?><?php echo smarty_function_homepage(array(), $this);?>
<?php endif; ?>" class="btn btn-gray back-btn"><i class="fa fa-arrow-circle-left"></i><?php echo smarty_function_gt(array('text' => 'Back'), $this);?>
</a>
                            <button class="btn btn-default refresh-btn"><i class="fa fa-refresh"></i></button>
                        </td>
                        <td colspan="2" class="hidden-xs total-td-desk-head"><?php echo smarty_function_gt(array('text' => 'Total price'), $this);?>
  = </td>
                        <td class="hidden-xs total-td-desk"><strong> DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['GRANDSUM']), $this);?>
</strong></td>
                        <td>
                             <?php if (! $this->_tpl_vars['outOfStock']): ?>
                            <a href="#" onClick="document.forms['checkout<?php echo $this->_tpl_vars['cart_shop_id']; ?>
'].submit()" class="btn btn-primary checkout-btn"><?php echo smarty_function_gt(array('text' => 'Checkout'), $this);?>
 <i class="fa fa-arrow-right"></i>
                            </a>
                             <?php endif; ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>
        <form name="checkout<?php echo $this->_tpl_vars['cart_shop_id']; ?>
" action='<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'checkout'), $this);?>
' method='post'>  
             <input type="hidden" name="cart_shop_id" value="<?php echo $this->_tpl_vars['cart_shop_id']; ?>
">
        </form>
              <?php endforeach; else: ?>
              <div align="center">  <?php echo smarty_function_gt(array('text' => 'No products in cart'), $this);?>
 </div>
          <?php endif; unset($_from); ?>       
        <!-- END CART WRAP 01 -->


    </div>
</div>