<?php /* Smarty version 2.6.28, created on 2018-02-03 06:51:38
         compiled from user/delivery.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pageaddvar', 'user/delivery.tpl', 1, false),array('function', 'gt', 'user/delivery.tpl', 15, false),array('function', 'modurl', 'user/delivery.tpl', 18, false),array('function', 'displayprice', 'user/delivery.tpl', 100, false),)), $this); ?>
<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "modules/ZSELEX/javascript/validation/style.css"), $this);?>
 
<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => 'http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/effects.js'), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => "modules/ZSELEX/javascript/validation/fabtabulous.js"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => "modules/ZSELEX/javascript/validation/validation.js"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => ($this->_tpl_vars['themepath'])."/javascript/delivery.js"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'stylesheet','value' => "modules/ZSELEX/lib/jquery-nicemodal-1.0/jquery-nicemodal.css?v=1.0"), $this);?>

<?php echo smarty_function_pageaddvar(array('name' => 'javascript','value' => "modules/ZSELEX/lib/jquery-nicemodal-1.0/jquery-nicemodal.js"), $this);?>
 



<div class="row">
    <div class="col-md-12">
         <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/cartmenu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <h2><?php echo smarty_function_gt(array('text' => 'Delivery Address'), $this);?>
</h2>

        <!-- delivery -->
        <form id="payments" name="payments" method="post" action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'paymentoptions','shop_id' => $_REQUEST['shop_id']), $this);?>
" class="form-horizontal">
         <input type="hidden" id="shop_id" value="<?php echo $_REQUEST['shop_id']; ?>
">
            <div class="delivery-wrap">
            <div class="delivery-address-details">
                
                    <div class="row">
                        <div class="col-sm-6">
                           <?php if ($this->_tpl_vars['showCheckbox'] > 0): ?>
                            <div class="checkbox">
                                <input <?php if ($_SESSION['checkoutinfo']['self_pickup']): ?>checked<?php endif; ?> type="checkbox" id="self_pickup" name="self_pickup" value="1">
                                <label for="self_pickup"><?php echo smarty_function_gt(array('text' => 'I will pick up the order in your shop myself'), $this);?>
</label>
                            </div>
                          
                            <div class="delivery-time-info">
                                <p><?php echo smarty_function_gt(array('text' => 'Your order will be delivered to you within'), $this);?>
&nbsp;<?php echo $this->_tpl_vars['delivery_time']; ?>
</p>
                            </div>
                            <div class="delivery-address">
                                 <b><?php echo $this->_tpl_vars['userinfo']['fname']; ?>
 <?php echo $this->_tpl_vars['userinfo']['lname']; ?>
<br /><?php echo $this->_tpl_vars['userinfo']['address']; ?>
<br /><?php echo $this->_tpl_vars['userinfo']['zip']; ?>
 <?php echo $this->_tpl_vars['userinfo']['city']; ?>

                            </br><?php echo $this->_tpl_vars['userinfo']['country']; ?>
</b>
                            </div>
                              <?php endif; ?>
                        </div>


                        <div class="col-sm-6">
                            <?php if ($this->_tpl_vars['showCheckbox'] > 0): ?>
                            <div class="checkbox">
                                <input type="checkbox" id="chng_shippingaddr" name="chng_shippingaddr" value="1">
                                <label for="chng_shippingaddr"><?php echo smarty_function_gt(array('text' => 'Choose a different delivery address'), $this);?>
</label>
                            </div>
                           <?php endif; ?>

                            <div class="form-horizontal delivery-new-address new_address" style="display: none;">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for=""><?php echo smarty_function_gt(array('text' => 'First Name'), $this);?>
: </label>
                                    <div class="col-sm-8">
                                        <input title="<?php echo smarty_function_gt(array('text' => 'Please enter your first name'), $this);?>
" name="diffAddr[fname]" type="text" placeholder="" id="fname" class="form-control required" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for=""><?php echo smarty_function_gt(array('text' => 'Last Name'), $this);?>
: </label>
                                    <div class="col-sm-8">
                                        <input type="text" title="<?php echo smarty_function_gt(array('text' => 'Please enter your last name'), $this);?>
" placeholder="" name="diffAddr[lname]" id="lname" class="form-control required" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for=""><?php echo smarty_function_gt(array('text' => 'Address'), $this);?>
: </label>
                                    <div class="col-sm-8">
                                        <textarea name="diffAddr[address]" title="<?php echo smarty_function_gt(array('text' => 'Please enter your address'), $this);?>
" id="address" class="form-control required" disabled></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label" for=""><?php echo smarty_function_gt(array('text' => 'Phone'), $this);?>
: </label>
                                    <div class="col-sm-8">
                                        <input type="text" placeholder="" id="phone" name="diffAddr[phone]" class="form-control required validate-digits" title="<?php echo smarty_function_gt(array('text' => 'Please enter a valid phone number'), $this);?>
" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 col-xs-12 control-label" for=""><?php echo smarty_function_gt(array('text' => 'ZIP code. city'), $this);?>
: </label>
                                    <div class="col-sm-3 col-xs-4">
                                        <input type="text" placeholder="" id="country_code" name="diffAddr[zip]" class="form-control required" title="<?php echo smarty_function_gt(array('text' => 'Please enter zip code'), $this);?>
" disabled>
                                    </div>
                                    <div class="col-sm-5 col-xs-8">
                                        <input type="text" placeholder="" id="telephone" name="diffAddr[city]" class="form-control required" title="<?php echo smarty_function_gt(array('text' => 'Please enter city'), $this);?>
" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="promo-code">
                                <h4><?php echo smarty_function_gt(array('text' => 'Discount code'), $this);?>
:</h4>
                                <p> <?php echo smarty_function_gt(array('text' => 'Enter discount code. Each voucher can only be used once. There can only be used one voucher per order:'), $this);?>
 </p>
                            </div>
                            <div class="col-sm-12 update-price">
                                <div class="form-inline">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="dis_code" <?php if ($_SESSION['checkoutinfo']['discount_code']): ?>value="<?php echo $_SESSION['checkoutinfo']['discount_code']; ?>
"<?php endif; ?> placeholder="<?php echo smarty_function_gt(array('text' => 'Enter Code..'), $this);?>
">
                                    </div>
                                    <button id="updatePrice" type="button" class="btn btn-default btn-update-price"><i class="fa fa-refresh"></i> <?php echo smarty_function_gt(array('text' => 'Update price'), $this);?>
</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="gtotalhidden" type="hidden" value="<?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['GRANDTOTAL']), $this);?>
">
                    <input id="gtotalallhidden" type="hidden" value="<?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['GRANDTOTAL_ALL']), $this);?>
">
                    <input id="shippinghidden" type="hidden" value="<?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['SHIPPING']), $this);?>
">
              
            </div>
            <div class="delivery-pricing-details">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo smarty_function_gt(array('text' => 'Your order'), $this);?>
</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo smarty_function_gt(array('text' => 'Price'), $this);?>
:</td>
                                    <td id="gtotal" class="price-dtl">DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['GRANDTOTAL']), $this);?>
</td>
                                </tr>
                                <tr class="active-color">
                                    <td><?php echo smarty_function_gt(array('text' => 'Discount'), $this);?>
:</td>
                                    <td class="price-dtl" id="discount_td"><?php echo $this->_tpl_vars['DISCOUNT']; ?>
%</td>
                                </tr>
                                <?php if ($this->_tpl_vars['showCheckbox'] > 0): ?>
                                <tr>
                                    <td><?php echo smarty_function_gt(array('text' => 'Shipping'), $this);?>
:</td>
                                    <td class="price-dtl" id="shippingtotal">DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['SHIPPING']), $this);?>
</td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td><?php echo smarty_function_gt(array('text' => 'VAT is'), $this);?>
:</td>
                                    <td class="price-dtl" id="vat_td">DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['VAT']), $this);?>
</td>
                                </tr>
                                <tr>
                                    <td><?php echo smarty_function_gt(array('text' => 'Total'), $this);?>
:</td>
                                    <td class="price-dtl" id="gtotalall">DKK <?php echo smarty_function_displayprice(array('amount' => $this->_tpl_vars['final_price']), $this);?>
 </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div class="checkbox">
                                            <input type="checkbox" title="<?php echo smarty_function_gt(array('text' => 'Please select the terms and conditions'), $this);?>
" id="termscondition" class="required">
                                            <label for="termscondition"> <?php echo smarty_function_gt(array('text' => 'I have read and accept this shops'), $this);?>
 <a href="#"><span class="delivery-pop-up" data-url="<?php echo $this->_tpl_vars['baseurl']; ?>
<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'info','func' => 'footerLink','key' => 'termsoftrade','shop_id' => $_REQUEST['shop_id']), $this);?>
"><?php echo smarty_function_gt(array('text' => 'terms and conditions'), $this);?>
</span></a></label>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="checkout-btn-wrap clearfix">
            <a href="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'shop','shop_id' => $this->_tpl_vars['shop_id']), $this);?>
" class="btn btn-warning cs-btn btn-info"><i class="fa fa-angle-left"></i> <?php echo smarty_function_gt(array('text' => 'Continue Shopping'), $this);?>
</a>
                          
           <button type="submit" class="btn btn-primary checkout-btn">
                <?php echo smarty_function_gt(array('text' => 'Go to payment'), $this);?>
 
                <i class="fa fa-arrow-right"></i>
           </button>
                  </div>
          </form>
        <!-- delivery -->
    </div>
</div>
        
         <script type="text/javascript"><?php echo '
		var valid2 = new Validation(\'payments\', {useTitles:true});
                 jQuery(\'.delivery-pop-up\').nicemodal({
        width: \'500px\',
        height: \'500px\',
        keyCodeToClose: 27,
        defaultCloseButton: true,
        closeOnClickOverlay: true,
        closeOnDblClickOverlay: false,
        // onOpenModal: function(){
        //     alert(\'Opened\');
        // },
        // onCloseModal: function(){
        //     alert(\'Closed\');
        // }
    });
            '; ?>
</script>