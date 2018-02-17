
<div class="row">
    <div class="col-md-12">
        {include file="user/cartmenu.tpl"}
        <h2>{gt text='Select Payment Method'}</h2>

        <!-- delivery -->
        <form id="payoptions" class="z-form" action="{modurl modname="ZSELEX" type="user" func="placeOrder" shop_id=$cart_shop_id}" method="post" enctype="application/x-www-form-urlencoded">
              <div class="paymentoptions-wrap">
                <div class="row">
                    <div class="col-sm-2 col-xs-3 tabs-left-wrap">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tabs-left sideways" id="tabs">
                            {assign var=val value=0}
                            {if $payButtonExist}
                            {if $paypal_info.enabled && $modvars.ZPayment.Paypal_enabled_general eq true}
                            {assign var=val value=$val+1}
                            <li {if $val eq 1} class="active" {/if} id="tab1-li">
                                <a href="#paypal-tab" data-toggle1="tab" id="tab1">
                                    <img src="{$themepath}/images/paypal.png" alt="" width="71" height="59">
                                </a>
                            </li>
                            {/if}
                            {if $netaxept_info.enabled && $modvars.ZPayment.Netaxept_enabled_general eq true}
                            {assign var=val value=$val+1}
                            <li {if $val eq 1} class="active" {/if} id="tab2-li">
                                <a href="#authorizenet-tab" data-toggle1="tab" id="tab2">
                                    <img src="{$themepath}/images/nets.png" alt="" width="71">
                                </a>
                            </li>
                            {/if}
                            {if $quickpay_info.enabled && $modvars.ZPayment.QuickPay_enabled_general eq true}
                            {assign var=val value=$val+1}
                            <li {if $val eq 1} class="active" {/if} id="tab3-li">
                                <a href="#quickpay-tab" data-toggle1="tab" id="tab3">
                                    <img src="{$themepath}/images/quickpay.png" alt="" width="71" height="59">
                                </a>
                            </li>
                            {/if}
                            {/if}
                            {if $directpay_info.enabled && $modvars.ZPayment.Directpay_enabled_general eq true}
                            {assign var=val value=$val+1}
                            <li {if $val eq 1} class="active" {/if} id="tab4-li">
                                <a href="#directpay-tab" data-toggle1="tab" id="tab4">
                                    <img src="{$themepath}/images/directpay.png" alt="" width="71">
                                </a>
                            </li>
                            {/if}

                        </ul>
                    </div>

                    <div class="col-sm-10 col-xs-9 tabs-right-wrap">
                        {assign var=val2 value=0}
                        {if $payButtonExist}
                        <!-- Tab panes -->
                        <div class="tab-content">
                            {if $paypal_info.enabled && $modvars.ZPayment.Paypal_enabled_general eq true}
                            {assign var=val2 value=$val2+1}
                            <div class="tab-pane tab-container {if $val2 eq 1} active {/if}" {*id="paypal-tab"*} id="tab1C">
                                <div class="inner-payment-logo">
                                    <img src="{$themepath}/images/paypal-1.png" alt="" width="109" height="27">
                                    <p>{gt text='Save time. Checkout securely. Pay without sharing your financial information.'}</p>
                                </div>
                                <div class="place-order-btn">
                                    <button type="submit" name="paytype" value="paypal" class="btn btn-primary checkout-btn">
                                        {gt text='Place Order'} <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="payment-opt-logo">
                                   {* <img src="{$themepath}/images/payment-options.jpg" alt="" width="248" height="37">*}
                                    {cardsaccepted shop_id=$smarty.request.shop_id}
                                </div>
                            </div>
                            {/if}
                            {if $netaxept_info.enabled && $modvars.ZPayment.Netaxept_enabled_general eq true}
                            {assign var=val2 value=$val2+1}
                            <div class="tab-pane tab-container {if $val2 eq 1} active {/if}" {*id="authorizenet-tab"*} id="tab2C">
                                <div class="inner-payment-logo">
                                    <img src="{$themepath}/images/nets-big.png" alt=""  >
                                    <p>{gt text='Save time. Checkout securely. Pay without sharing your financial information.'}</p>
                                </div>
                                <div class="place-order-btn">
                                    <button type="submit" name="paytype" value="netaxept" class="btn btn-primary checkout-btn">
                                        {gt text='Place Order'} <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="payment-opt-logo">
                                   {* <img src="{$themepath}/images/payment-options.jpg" alt="" width="248" height="37">*}
                                   {cardsaccepted shop_id=$smarty.request.shop_id}
                                </div>
                            </div>
                            {/if}
                            {if $quickpay_info.enabled && $modvars.ZPayment.QuickPay_enabled_general eq true}
                            {assign var=val2 value=$val2+1}
                            <div class="tab-pane tab-container {if $val2 eq 1} active {/if}" {*id="quickpay-tab"*} id="tab3C">
                                <div class="inner-payment-logo">
                                    <img src="{$themepath}/images/quickpay-1.png" alt="" width="133" height="27">
                                    <p>{gt text='Save time. Checkout securely. Pay without sharing your financial information.'}</p>
                                </div>
                                <div class="place-order-btn">
                                    {*<a href="#" class="btn btn-primary checkout-btn">*}
                                        <button type="submit" name="paytype" value="quickpay" class="btn btn-primary checkout-btn">
                                            {gt text='Place Order'} <i class="fa fa-arrow-right"></i>
                                        </button>
                                        {* </a>*}
                                </div>
                                <div class="payment-opt-logo">
                                  {*  <img src="{$themepath}/images/payment-options.jpg" alt="" width="248" height="37">*}
                                  {cardsaccepted shop_id=$smarty.request.shop_id}
                                </div>
                            </div>
                            {/if}
                            {if $directpay_info.enabled && $modvars.ZPayment.Directpay_enabled_general eq true}
                            {assign var=val2 value=$val2+1}
                            <div class="tab-pane tab-container {if $val2 eq 1} active {/if}" {*id="directpay-tab"*} id="tab4C">
                                <div class="inner-payment-logo">
                                    <img src="{$themepath}/images/direct-pay-big.png" alt="" >
                                    <p> {$directpay_info.info|wordwrap:100:"<br/>":true}</p>
                                </div>
                                <div class="place-order-btn">
                                    <button type="submit" name="paytype" value="directpay" class="btn btn-primary checkout-btn">
                                        {gt text='Place Order'} <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="payment-opt-logo">
                                   {* <img src="{$themepath}/images/payment-options.jpg" alt="" width="248" height="37">*}
                                   {cardsaccepted shop_id=$smarty.request.shop_id}
                                </div>
                            </div>
                            {/if}
                        </div>
                        {/if}
                    </div>

                </div>
            </div>
        </form>
        <!-- delivery -->
    </div>
</div>

<script>
    jQuery(document).ready(function () {


       jQuery('#tabs li a').click(function () {
           //alert(this.id);
           jQuery('#tabs .active').removeClass('active');
           jQuery('#'+this.id+'-li').addClass('active');
           jQuery('.tab-container').hide();
           jQuery('#' + this.id + 'C').fadeIn('slow');
           
        });

    });
</script>