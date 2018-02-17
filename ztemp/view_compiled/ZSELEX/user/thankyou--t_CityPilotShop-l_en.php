<?php /* Smarty version 2.6.28, created on 2017-10-29 15:32:38
         compiled from user/thankyou.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'user/thankyou.tpl', 5, false),array('function', 'modurl', 'user/thankyou.tpl', 34, false),array('function', 'displayoptions', 'user/thankyou.tpl', 50, false),array('function', 'displayprice', 'user/thankyou.tpl', 61, false),array('modifier', 'cleantext', 'user/thankyou.tpl', 45, false),array('modifier', 'safetext', 'user/thankyou.tpl', 56, false),)), $this); ?>

<div class="row">
    <div class="col-md-12">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/cartmenu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <h2><?php echo smarty_function_gt(array('text' => 'Order Summary'), $this);?>
</h2>
        <h2><?php echo smarty_function_gt(array('text' => 'Your Order Id'), $this);?>
 : <?php echo $this->_tpl_vars['order_id']; ?>
</h2>
        <h3><?php echo smarty_function_gt(array('text' => 'Congratulations on your order has gone through - you will receive an order confirmation by email. If you have not received it within 10 minutes, please contact us'), $this);?>
</h3>
        <?php if ($this->_tpl_vars['payment_type'] == 'directpay'): ?>

        <?php echo $this->_tpl_vars['directpay']['info']; ?>

        <?php endif; ?>  

        <div class="city-box">
            <span><?php echo smarty_function_gt(array('text' => 'From Seller'), $this);?>
  :</span><a href="#"><?php echo $this->_tpl_vars['ownername']; ?>
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
                <?php $_from = $this->_tpl_vars['orderDetails']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k1'] => $this->_tpl_vars['v1']):
?>  
                <tr>
                    <td class="product-td" data-th="Product">
                        <div class="clearfix">
                            <div class="col-sm-3">
                                <div class="product-image-box">
                                    <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'productview','id' => $this->_tpl_vars['v1']['product_id']), $this);?>
">
                                       <?php $this->assign('imagepath', "zselexdata/".($this->_tpl_vars['shop_id'])."/products/thumb/".($this->_tpl_vars['v1']['prd_image'])); ?>
                                       <?php if (file_exists ( $this->_tpl_vars['imagepath'] ) && $this->_tpl_vars['v1']['prd_image'] != ''): ?> 
                                       <img src="zselexdata/<?php echo $this->_tpl_vars['shop_id']; ?>
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
                        <?php echo $this->_tpl_vars['v1']['quantity']; ?>

                    </td>
                    <td class="subtotal-td" data-th="Subtotal">DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['v1']['total']), $this);?>
</td>
                    <td class="actions" data-th="">

                    </td>
                </tr>

                <?php endforeach; endif; unset($_from); ?>

            </tbody>
            <tfoot>
                <tr class="visible-xs">
                    <td class="text-center total-td">
                        <p><?php echo smarty_function_gt(array('text' => 'Shipping'), $this);?>
 = <?php if ($this->_tpl_vars['shippingVal'] < 1): ?><?php echo smarty_function_gt(array('text' => 'Free'), $this);?>
<?php else: ?>DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['shippingVal']), $this);?>
<?php endif; ?></p>
                        <p><?php echo smarty_function_gt(array('text' => 'Vat'), $this);?>
 = <?php if ($this->_tpl_vars['vat'] > 0): ?>DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['vat']), $this);?>
<br /><?php endif; ?></p>
                        <strong><?php echo smarty_function_gt(array('text' => 'Total'), $this);?>
 = <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['grand_total_all']), $this);?>
</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'site','shop_id' => $this->_tpl_vars['shop_id']), $this);?>
" class="btn btn-warning cs-btn btn-info"><i class="fa fa-angle-left"></i> <?php echo smarty_function_gt(array('text' => 'Continue Shopping'), $this);?>
</a>
                        <?php if ($this->_tpl_vars['cartCount']): ?>   
                        <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'cart'), $this);?>
" class="btn btn-gray back-btn"><i class="fa fa-arrow-circle-left"></i> <?php echo smarty_function_gt(array('text' => 'Payment next shop'), $this);?>
</a>
                        <?php endif; ?>
                                            </td>
                    <td colspan="2" class="hidden-xs total-td-desk-head">
                        <p><?php echo smarty_function_gt(array('text' => 'Shipping'), $this);?>
 =</p>
                       <?php if ($this->_tpl_vars['vat'] > 0): ?> <p> <?php echo smarty_function_gt(array('text' => 'Vat'), $this);?>
 = </p> <?php endif; ?>
                        <p> <?php echo smarty_function_gt(array('text' => 'Total price'), $this);?>
  =  </p>
                    </td>
                    <td class="hidden-xs total-td-desk">
                        <p> <?php if ($this->_tpl_vars['shippingVal'] < 1): ?><?php echo smarty_function_gt(array('text' => 'Free'), $this);?>
<?php else: ?>DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['shippingVal']), $this);?>
<?php endif; ?></p>
                       <?php if ($this->_tpl_vars['vat'] > 0): ?> <p>  DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['vat']), $this);?>
</p><?php endif; ?>
                        <p>  <strong> DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['grand_total_all']), $this);?>
</strong> </p>
                    </td>
                    <td>


                    </td>
                </tr>
            </tfoot>
        </table>



        
    <!-- END CART WRAP 01 -->


</div>
</div>