<?php /* Smarty version 2.6.28, created on 2017-10-29 15:26:54
         compiled from user/order.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'user/order.tpl', 1, false),array('function', 'gt', 'user/order.tpl', 5, false),array('function', 'modurl', 'user/order.tpl', 28, false),array('function', 'displayoptions', 'user/order.tpl', 44, false),array('function', 'displayprice', 'user/order.tpl', 55, false),array('modifier', 'cleantext', 'user/order.tpl', 39, false),array('modifier', 'safetext', 'user/order.tpl', 50, false),array('modifier', 'number_format', 'user/order.tpl', 105, false),)), $this); ?>
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'modules/ZSELEX/javascript/order.js'), $this);?>

<div class="row">
    <div class="col-md-12">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/cartmenu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <h2><?php echo smarty_function_gt(array('text' => 'Shopping Cart'), $this);?>
</h2>

        <div class="city-box">
            <span><?php echo smarty_function_gt(array('text' => 'Shop'), $this);?>
  :</span><a href="#"><?php echo $this->_tpl_vars['shop_name']; ?>
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
                <?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
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
                       <?php if ($this->_tpl_vars['vat'] > 0): ?> <p><?php echo smarty_function_gt(array('text' => 'Vat'), $this);?>
 = DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['vat']), $this);?>
</p><?php endif; ?>
                        <strong><?php echo smarty_function_gt(array('text' => 'Total'), $this);?>
 = <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['grand_total_all']), $this);?>
</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                                            </td>
                    <td colspan="2" class="hidden-xs total-td-desk-head">
                        <p><?php echo smarty_function_gt(array('text' => 'Shipping'), $this);?>
 =</p>
                        <?php if ($this->_tpl_vars['vat'] > 0): ?><p> <?php echo smarty_function_gt(array('text' => 'Vat'), $this);?>
 = </p><?php endif; ?>
                        <p> <?php echo smarty_function_gt(array('text' => 'Total price'), $this);?>
  =  </p>
                    </td>
                    <td class="hidden-xs total-td-desk">
                        <p> <?php if ($this->_tpl_vars['shippingVal'] < 1): ?><?php echo smarty_function_gt(array('text' => 'Free'), $this);?>
<?php else: ?>DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['shippingVal']), $this);?>
<?php endif; ?></p>
                       <?php if ($this->_tpl_vars['vat'] > 0): ?> <p>  DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['vat']), $this);?>
<br /></p><?php endif; ?>
                        <p>  <strong> DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['grand_total_all']), $this);?>
</strong> </p>
                    </td>
                    <td>

                        <?php if ($this->_tpl_vars['paytype'] == 'paypal'): ?>
                        <?php if ($this->_tpl_vars['paypal_info']['test_mode']): ?>
                        <form id="payforms" method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
                            <?php else: ?>
                            <form id="payforms" method="post" action="https://www.paypal.com/cgi-bin/webscr">      
                                <?php endif; ?>    

                                <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['paypal_info']['business_email']; ?>
" name="business">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['order_id']; ?>
" name="item_name">
                                <input type="hidden" value="1" name="item_number">
                                <input type="hidden" id="pp_amount" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['grand_total_all'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2) : number_format($_tmp, 2)); ?>
" name="amount">
                                <input type="hidden" value="DKK" name="currency_code">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['userinfo']['fname']; ?>
" name="first_name">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['userinfo']['lname']; ?>
" name="last_name">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['userinfo']['address']; ?>
" name="address1">
                                <input type="hidden" value="" name="address2">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['userinfo']['city']; ?>
" name="city">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['userinfo']['state']; ?>
" name="state">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['userinfo']['zip']; ?>
" name="zip">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['userinfo']['country']; ?>
" name="country">
                                <input type="hidden" value="0" name="address_override">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['userinfo']['email']; ?>
" name="email">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['order_id']; ?>
 - CityPilot" name="invoice">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['thislang']; ?>
" name="lc">
                                <input type="hidden" value="2" name="rm">
                                <input type="hidden" value="1" name="no_note">
                                <input type="hidden" value="utf-8" name="charset">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['pp_return_url']; ?>
" name="return">
                                <!--<input type="hidden" value="http://localhost/opencart/upload/index.php?route=payment/pp_standard/callback" name="notify_url">-->
                                <input type="hidden" value="<?php echo $this->_tpl_vars['pp_cancel_url']; ?>
" name="cancel_return">
                                <input type="hidden" value="authorization" name="paymentaction">
                                <input type="hidden" value="<?php echo $this->_tpl_vars['shop_id']; ?>
" name="custom">

                                <button class="btn btn-primary checkout-btn pp_paybtns" type="button"><?php echo smarty_function_gt(array('text' => 'Pay with PayPal'), $this);?>
<?php if ($this->_tpl_vars['paypal_info']['test_mode']): ?>&nbsp;(<?php echo smarty_function_gt(array('text' => 'test mode'), $this);?>
)<?php endif; ?></button>
                                <div style="display:none;margin-right:90px" id="redirecting" class="right">
                                    <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
modules/ZSELEX/images/ajax-loading.gif">
                                </div>
                            </form>
                            <?php elseif ($this->_tpl_vars['paytype'] == 'netaxept'): ?>    
                            <form id="payforms" action='<?php echo $this->_tpl_vars['netaxept']['terminal_url']; ?>
' method='post'>
                                <button class="btn btn-primary checkout-btn na_paybtns paybtns" type="button"><?php echo smarty_function_gt(array('text' => 'Pay with Netaxept'), $this);?>
<?php if ($this->_tpl_vars['netaxept_info']['test_mode']): ?>&nbsp;(<?php echo smarty_function_gt(array('text' => 'test mode'), $this);?>
)<?php endif; ?></button>
                                <div style="display:none;margin-right:90px" id="redirecting" class="right">
                                    <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
modules/ZSELEX/images/ajax-loading.gif">
                                </div>
                            </form>
                            <?php elseif ($this->_tpl_vars['paytype'] == 'quickpay'): ?>
                            <form id="payforms"  action="https://payment.quickpay.net" method="post">

                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="version" value="v10">
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="merchant_id" value="<?php echo $this->_tpl_vars['quickpay_info']['merchant_id']; ?>
">
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="agreement_id" value="<?php echo $this->_tpl_vars['quickpay_info']['agreement_id']; ?>
">
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="order_id" value="<?php echo $this->_tpl_vars['quickpay_info']['ordernumber']; ?>
">
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" id="qp_amount" name="amount" value="<?php echo $this->_tpl_vars['quickpay_info']['amount']; ?>
">
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="currency" value="<?php echo $this->_tpl_vars['quickpay_info']['currency']; ?>
">
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="continueurl" value="<?php echo $this->_tpl_vars['quickpay_info']['continueurl']; ?>
">
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="cancelurl" value="<?php echo $this->_tpl_vars['quickpay_info']['cancelurl']; ?>
">
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="callbackurl" value="<?php echo $this->_tpl_vars['quickpay_info']['callbackurl']; ?>
">
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="variables[CUSTOM_shop_id]" value="<?php echo $this->_tpl_vars['shop_id']; ?>
" />
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="variables[CUSTOM_user_id]" value="<?php echo $this->_tpl_vars['user_id']; ?>
" />
                                <input type="<?php echo $this->_tpl_vars['text_type']; ?>
" name="checksum" value="<?php echo $this->_tpl_vars['checksum']; ?>
">

                                <button class="btn btn-primary checkout-btn qp_button paybtns" type="button"><?php echo smarty_function_gt(array('text' => 'Pay with QuickPay'), $this);?>
</button>
                                <div style="display:none;margin-right:90px" id="redirecting" class="right">
                                    <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
modules/ZSELEX/images/ajax-loading.gif">
                                </div>
                            </form>
                            <?php elseif ($this->_tpl_vars['paytype'] == 'epay'): ?>
                            <form id="payforms"  action="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/Default.aspx" method="post">

                                <input type="hidden" name="merchantnumber" value="<?php echo $this->_tpl_vars['epayForm']['merchant_number']; ?>
">
                                <input type="hidden" name="orderid" value="<?php echo $this->_tpl_vars['epayForm']['ordernumber']; ?>
"> 
                                <input type="hidden" id="ep_amount" name="amount" value="<?php echo $this->_tpl_vars['epayForm']['amount']; ?>
"> 
                                <input type="hidden" name="currency" value="<?php echo $this->_tpl_vars['epayForm']['currency']; ?>
">
                                <input type="hidden" name="windowstate" value="<?php echo $this->_tpl_vars['epayForm']['windowstate']; ?>
"> 
                                <input type="hidden" name="instantcallback" value="<?php echo $this->_tpl_vars['epayForm']['instantcallback']; ?>
"> 
                                <input type="hidden" name="ownreceipt" value="<?php echo $this->_tpl_vars['epayForm']['ownreceipt']; ?>
"> 
                                <input type="hidden" name="callbackurl" value="<?php echo $this->_tpl_vars['epayForm']['callbackurl']; ?>
"> 
                                <input type="hidden" name="ordertext" value="<?php echo $this->_tpl_vars['epayForm']['ordertext']; ?>
"> 
                                <input type="hidden" name="description" value="<?php echo $this->_tpl_vars['epayForm']['description']; ?>
"> 
                                <?php if ($this->_tpl_vars['epayForm']['set_hash']): ?>
                                <input type="hidden" name="hash" value="<?php echo $this->_tpl_vars['epayForm']['hash']; ?>
"> 
                                <?php endif; ?>
                                <input type="hidden" name="accepturl" value="<?php echo $this->_tpl_vars['epayForm']['accepturl']; ?>
"> 
                                <input type="hidden" name="cancelurl" value="<?php echo $this->_tpl_vars['epayForm']['cancelurl']; ?>
"> 
                                <button class="btn btn-primary checkout-btn paybtns ep_button" type="button"><?php echo smarty_function_gt(array('text' => 'Pay with ePay'), $this);?>
<?php if ($this->_tpl_vars['epay_info']['test_mode']): ?>&nbsp;(<?php echo smarty_function_gt(array('text' => 'test mode'), $this);?>
)<?php endif; ?></button>
                                <div style="display:none;margin-right:90px" id="redirecting" class="right">
                                    <img src="<?php echo $this->_tpl_vars['baseurl']; ?>
modules/ZSELEX/images/ajax-loading.gif">
                                </div>
                            </form>
                            <?php elseif ($this->_tpl_vars['paytype'] == 'printorder'): ?>
                            <form action='<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'orderConfirmation','theme' => 'printer'), $this);?>
' method='post'>
                                <button type="submit" class="btn btn-primary checkout-btn pp_paybtns"><?php echo smarty_function_gt(array('text' => 'Print Order'), $this);?>
<span class="Right_Arrow"></span></button>
                            </form>
                            <?php endif; ?>
                    </td>
                </tr>
            </tfoot>
        </table>
                     <input type="hidden" id="deleveryUrl" value="<?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'paymentoptions','shop_id' => $this->_tpl_vars['shop_id']), $this);?>
">


        
    <!-- END CART WRAP 01 -->


</div>
</div>