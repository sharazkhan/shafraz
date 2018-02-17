<?php /* Smarty version 2.6.28, created on 2017-10-29 15:24:01
         compiled from user/paymentoptions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'gt', 'user/paymentoptions.tpl', 5, false),array('function', 'modurl', 'user/paymentoptions.tpl', 8, false),array('function', 'cardsaccepted', 'user/paymentoptions.tpl', 72, false),array('modifier', 'wordwrap', 'user/paymentoptions.tpl', 119, false),)), $this); ?>

<div class="row">
    <div class="col-md-12">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "user/cartmenu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <h2><?php echo smarty_function_gt(array('text' => 'Select Payment Method'), $this);?>
</h2>

        <!-- delivery -->
        <form id="payoptions" class="z-form" action="<?php echo smarty_function_modurl(array('modname' => 'ZSELEX','type' => 'user','func' => 'placeOrder','shop_id' => $this->_tpl_vars['cart_shop_id']), $this);?>
" method="post" enctype="application/x-www-form-urlencoded">
              <div class="paymentoptions-wrap">
                <div class="row">
                    <div class="col-sm-2 col-xs-3 tabs-left-wrap">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tabs-left sideways" id="tabs">
                            <?php $this->assign('val', 0); ?>
                            <?php if ($this->_tpl_vars['payButtonExist']): ?>
                            <?php if ($this->_tpl_vars['paypal_info']['enabled'] && $this->_tpl_vars['modvars']['ZPayment']['Paypal_enabled_general'] == true): ?>
                            <?php $this->assign('val', $this->_tpl_vars['val']+1); ?>
                            <li <?php if ($this->_tpl_vars['val'] == 1): ?> class="active" <?php endif; ?> id="tab1-li">
                                <a href="#paypal-tab" data-toggle1="tab" id="tab1">
                                    <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/paypal.png" alt="" width="71" height="59">
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['netaxept_info']['enabled'] && $this->_tpl_vars['modvars']['ZPayment']['Netaxept_enabled_general'] == true): ?>
                            <?php $this->assign('val', $this->_tpl_vars['val']+1); ?>
                            <li <?php if ($this->_tpl_vars['val'] == 1): ?> class="active" <?php endif; ?> id="tab2-li">
                                <a href="#authorizenet-tab" data-toggle1="tab" id="tab2">
                                    <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/nets.png" alt="" width="71">
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['quickpay_info']['enabled'] && $this->_tpl_vars['modvars']['ZPayment']['QuickPay_enabled_general'] == true): ?>
                            <?php $this->assign('val', $this->_tpl_vars['val']+1); ?>
                            <li <?php if ($this->_tpl_vars['val'] == 1): ?> class="active" <?php endif; ?> id="tab3-li">
                                <a href="#quickpay-tab" data-toggle1="tab" id="tab3">
                                    <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/quickpay.png" alt="" width="71" height="59">
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['directpay_info']['enabled'] && $this->_tpl_vars['modvars']['ZPayment']['Directpay_enabled_general'] == true): ?>
                            <?php $this->assign('val', $this->_tpl_vars['val']+1); ?>
                            <li <?php if ($this->_tpl_vars['val'] == 1): ?> class="active" <?php endif; ?> id="tab4-li">
                                <a href="#directpay-tab" data-toggle1="tab" id="tab4">
                                    <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/directpay.png" alt="" width="71">
                                </a>
                            </li>
                            <?php endif; ?>

                        </ul>
                    </div>

                    <div class="col-sm-10 col-xs-9 tabs-right-wrap">
                        <?php $this->assign('val2', 0); ?>
                        <?php if ($this->_tpl_vars['payButtonExist']): ?>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <?php if ($this->_tpl_vars['paypal_info']['enabled'] && $this->_tpl_vars['modvars']['ZPayment']['Paypal_enabled_general'] == true): ?>
                            <?php $this->assign('val2', $this->_tpl_vars['val2']+1); ?>
                            <div class="tab-pane tab-container <?php if ($this->_tpl_vars['val2'] == 1): ?> active <?php endif; ?>"  id="tab1C">
                                <div class="inner-payment-logo">
                                    <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/paypal-1.png" alt="" width="109" height="27">
                                    <p><?php echo smarty_function_gt(array('text' => 'Save time. Checkout securely. Pay without sharing your financial information.'), $this);?>
</p>
                                </div>
                                <div class="place-order-btn">
                                    <button type="submit" name="paytype" value="paypal" class="btn btn-primary checkout-btn">
                                        <?php echo smarty_function_gt(array('text' => 'Place Order'), $this);?>
 <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="payment-opt-logo">
                                                                       <?php echo smarty_function_cardsaccepted(array('shop_id' => $_REQUEST['shop_id']), $this);?>

                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['netaxept_info']['enabled'] && $this->_tpl_vars['modvars']['ZPayment']['Netaxept_enabled_general'] == true): ?>
                            <?php $this->assign('val2', $this->_tpl_vars['val2']+1); ?>
                            <div class="tab-pane tab-container <?php if ($this->_tpl_vars['val2'] == 1): ?> active <?php endif; ?>"  id="tab2C">
                                <div class="inner-payment-logo">
                                    <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/nets-big.png" alt=""  >
                                    <p><?php echo smarty_function_gt(array('text' => 'Save time. Checkout securely. Pay without sharing your financial information.'), $this);?>
</p>
                                </div>
                                <div class="place-order-btn">
                                    <button type="submit" name="paytype" value="netaxept" class="btn btn-primary checkout-btn">
                                        <?php echo smarty_function_gt(array('text' => 'Place Order'), $this);?>
 <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="payment-opt-logo">
                                                                      <?php echo smarty_function_cardsaccepted(array('shop_id' => $_REQUEST['shop_id']), $this);?>

                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['quickpay_info']['enabled'] && $this->_tpl_vars['modvars']['ZPayment']['QuickPay_enabled_general'] == true): ?>
                            <?php $this->assign('val2', $this->_tpl_vars['val2']+1); ?>
                            <div class="tab-pane tab-container <?php if ($this->_tpl_vars['val2'] == 1): ?> active <?php endif; ?>"  id="tab3C">
                                <div class="inner-payment-logo">
                                    <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/quickpay-1.png" alt="" width="133" height="27">
                                    <p><?php echo smarty_function_gt(array('text' => 'Save time. Checkout securely. Pay without sharing your financial information.'), $this);?>
</p>
                                </div>
                                <div class="place-order-btn">
                                                                            <button type="submit" name="paytype" value="quickpay" class="btn btn-primary checkout-btn">
                                            <?php echo smarty_function_gt(array('text' => 'Place Order'), $this);?>
 <i class="fa fa-arrow-right"></i>
                                        </button>
                                                                        </div>
                                <div class="payment-opt-logo">
                                                                    <?php echo smarty_function_cardsaccepted(array('shop_id' => $_REQUEST['shop_id']), $this);?>

                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['directpay_info']['enabled'] && $this->_tpl_vars['modvars']['ZPayment']['Directpay_enabled_general'] == true): ?>
                            <?php $this->assign('val2', $this->_tpl_vars['val2']+1); ?>
                            <div class="tab-pane tab-container <?php if ($this->_tpl_vars['val2'] == 1): ?> active <?php endif; ?>"  id="tab4C">
                                <div class="inner-payment-logo">
                                    <img src="<?php echo $this->_tpl_vars['themepath']; ?>
/images/direct-pay-big.png" alt="" >
                                    <p> <?php echo ((is_array($_tmp=$this->_tpl_vars['directpay_info']['info'])) ? $this->_run_mod_handler('wordwrap', true, $_tmp, 100, "<br/>", true) : smarty_modifier_wordwrap($_tmp, 100, "<br/>", true)); ?>
</p>
                                </div>
                                <div class="place-order-btn">
                                    <button type="submit" name="paytype" value="directpay" class="btn btn-primary checkout-btn">
                                        <?php echo smarty_function_gt(array('text' => 'Place Order'), $this);?>
 <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="payment-opt-logo">
                                                                      <?php echo smarty_function_cardsaccepted(array('shop_id' => $_REQUEST['shop_id']), $this);?>

                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </form>
        <!-- delivery -->
    </div>
</div>

<script><?php echo '
    jQuery(document).ready(function () {


       jQuery(\'#tabs li a\').click(function () {
           //alert(this.id);
           jQuery(\'#tabs .active\').removeClass(\'active\');
           jQuery(\'#\'+this.id+\'-li\').addClass(\'active\');
           jQuery(\'.tab-container\').hide();
           jQuery(\'#\' + this.id + \'C\').fadeIn(\'slow\');
           
        });

    });
'; ?>
</script>