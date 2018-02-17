{pageaddvar name='javascript' value='modules/ZSELEX/javascript/order.js'}
<div class="row">
    <div class="col-md-12">
        {include file="user/cartmenu.tpl"}
        <h2>{gt text='Shopping Cart'}</h2>

        <div class="city-box">
            <span>{gt text='Shop'}  :</span><a href="#">{$shop_name}</a>
        </div>

        <table id="cart" class="table table-hover table-condensed cart-table">
            <thead>
                <tr>
                    <th style="width:42%">{gt text='Product'}</th>
                    <th style="width:16%">{gt text='Price'}</th>
                    <th style="width:16%">{gt text='Quantity'}</th>
                    <th style="width:22%">{gt text='Subtotal'}</th>
                    <th style="width:4%"></th>
                </tr>
            </thead>
            <tbody>
                {foreach  from=$products  key=k1 item=v1}  
                <tr>
                    <td class="product-td" data-th="Product">
                        <div class="clearfix">
                            <div class="col-sm-3">
                                <div class="product-image-box">
                                    <a href="{modurl modname="ZSELEX" type="user" func="productview" id=$v1.product_id}">
                                       {assign var="imagepath" value="zselexdata/`$shop_id`/products/thumb/`$v1.prd_image`"}
                                       {if file_exists($imagepath)  && $v1.prd_image neq ''} 
                                       <img src="zselexdata/{$shop_id}/products/thumb/{$v1.prd_image}" alt="{$v1.product_name}" class="img-responsive"/>
                                        {else}
                                        <img alt="{$v1.product_name}"  src="{$themepath}/images/no-image.jpg"  width="100" class="img-responsive"/>
                                        {/if} 
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <h4 class="product-name-head">{$v1.product_name|cleantext}{if $v1.outofstock}<font color="red">&nbsp;***</font>{/if}</h4>
                                <p>{$v1.prd_description|cleantext}</p>

                                {if $v1.cart_content!=''}

                                {displayoptions options=$v1.cart_content}
                                {/if}
                                {if $v1.enable_question}

                                <p>{$v1.prd_question}</p>:

                                <input autocomplete="off" name="prd_answer[{$v1.cart_id}+{$v1.product_id}]"  value='{$v1.prd_answer|safetext|cleantext}'>
                                {/if}
                            </div>
                        </div>
                    </td>
                    <td class="price-td" data-th="Price">DKK {displayprice amount=$v1.price}</td>
                    <td class="quantity-td" data-th="Quantity">
                        {$v1.quantity}
                    </td>
                    <td class="subtotal-td" data-th="Subtotal">DKK {displayprice amount=$v1.total}</td>
                    <td class="actions" data-th="">

                    </td>
                </tr>

                {/foreach}

            </tbody>
            <tfoot>
                <tr class="visible-xs">
                    <td class="text-center total-td">
                        <p>{gt text='Shipping'} = {if $shippingVal < 1}{gt text='Free'}{else}DKK {displayprice amount=$shippingVal}{/if}</p>
                       {if $vat > 0} <p>{gt text='Vat'} = DKK {displayprice amount=$vat}</p>{/if}
                        <strong>{gt text='Total'} = {displayprice amount=$grand_total_all}</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        {* <a href="#" class="btn btn-warning cs-btn btn-info"><i class="fa fa-angle-left"></i> {gt text='Continue Shopping'}</a>
                        <a href="#" class="btn btn-gray back-btn"><i class="fa fa-arrow-circle-left"></i>{gt text='Back'}</a>
                        <button class="btn btn-default refresh-btn"><i class="fa fa-refresh"></i></button>*}
                    </td>
                    <td colspan="2" class="hidden-xs total-td-desk-head">
                        <p>{gt text='Shipping'} =</p>
                        {if $vat > 0}<p> {gt text='Vat'} = </p>{/if}
                        <p> {gt text='Total price'}  =  </p>
                    </td>
                    <td class="hidden-xs total-td-desk">
                        <p> {if $shippingVal < 1}{gt text='Free'}{else}DKK {displayprice amount=$shippingVal}{/if}</p>
                       {if $vat > 0} <p>  DKK {displayprice amount=$vat}<br /></p>{/if}
                        <p>  <strong> DKK {displayprice amount=$grand_total_all}</strong> </p>
                    </td>
                    <td>

                        {if $paytype eq 'paypal'}
                        {if $paypal_info.test_mode}
                        <form id="payforms" method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
                            {else}
                            <form id="payforms" method="post" action="https://www.paypal.com/cgi-bin/webscr">      
                                {/if}    

                                <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" value="{$paypal_info.business_email}" name="business">
                                <input type="hidden" value="{$order_id}" name="item_name">
                                <input type="hidden" value="1" name="item_number">
                                <input type="hidden" id="pp_amount" value="{$grand_total_all|number_format:2}" name="amount">
                                <input type="hidden" value="DKK" name="currency_code">
                                <input type="hidden" value="{$userinfo.fname}" name="first_name">
                                <input type="hidden" value="{$userinfo.lname}" name="last_name">
                                <input type="hidden" value="{$userinfo.address}" name="address1">
                                <input type="hidden" value="" name="address2">
                                <input type="hidden" value="{$userinfo.city}" name="city">
                                <input type="hidden" value="{$userinfo.state}" name="state">
                                <input type="hidden" value="{$userinfo.zip}" name="zip">
                                <input type="hidden" value="{$userinfo.country}" name="country">
                                <input type="hidden" value="0" name="address_override">
                                <input type="hidden" value="{$userinfo.email}" name="email">
                                <input type="hidden" value="{$order_id} - CityPilot" name="invoice">
                                <input type="hidden" value="{$thislang}" name="lc">
                                <input type="hidden" value="2" name="rm">
                                <input type="hidden" value="1" name="no_note">
                                <input type="hidden" value="utf-8" name="charset">
                                <input type="hidden" value="{$pp_return_url}" name="return">
                                <!--<input type="hidden" value="http://localhost/opencart/upload/index.php?route=payment/pp_standard/callback" name="notify_url">-->
                                <input type="hidden" value="{$pp_cancel_url}" name="cancel_return">
                                <input type="hidden" value="authorization" name="paymentaction">
                                <input type="hidden" value="{$shop_id}" name="custom">

                                <button class="btn btn-primary checkout-btn pp_paybtns" type="button">{gt text='Pay with PayPal'}{if $paypal_info.test_mode}&nbsp;({gt text='test mode'}){/if}</button>
                                <div style="display:none;margin-right:90px" id="redirecting" class="right">
                                    <img src="{$baseurl}modules/ZSELEX/images/ajax-loading.gif">
                                </div>
                            </form>
                            {elseif $paytype eq 'netaxept'}    
                            <form id="payforms" action='{$netaxept.terminal_url}' method='post'>
                                <button class="btn btn-primary checkout-btn na_paybtns paybtns" type="button">{gt text='Pay with Netaxept'}{if $netaxept_info.test_mode}&nbsp;({gt text='test mode'}){/if}</button>
                                <div style="display:none;margin-right:90px" id="redirecting" class="right">
                                    <img src="{$baseurl}modules/ZSELEX/images/ajax-loading.gif">
                                </div>
                            </form>
                            {elseif $paytype eq 'quickpay'}
                            <form id="payforms"  action="https://payment.quickpay.net" method="post">

                                <input type="{$text_type}" name="version" value="v10">
                                <input type="{$text_type}" name="merchant_id" value="{$quickpay_info.merchant_id}">
                                <input type="{$text_type}" name="agreement_id" value="{$quickpay_info.agreement_id}">
                                <input type="{$text_type}" name="order_id" value="{$quickpay_info.ordernumber}">
                                <input type="{$text_type}" id="qp_amount" name="amount" value="{$quickpay_info.amount}">
                                <input type="{$text_type}" name="currency" value="{$quickpay_info.currency}">
                                <input type="{$text_type}" name="continueurl" value="{$quickpay_info.continueurl}">
                                <input type="{$text_type}" name="cancelurl" value="{$quickpay_info.cancelurl}">
                                <input type="{$text_type}" name="callbackurl" value="{$quickpay_info.callbackurl}">
                                <input type="{$text_type}" name="variables[CUSTOM_shop_id]" value="{$shop_id}" />
                                <input type="{$text_type}" name="variables[CUSTOM_user_id]" value="{$user_id}" />
                                <input type="{$text_type}" name="checksum" value="{$checksum}">

                                <button class="btn btn-primary checkout-btn qp_button paybtns" type="button">{gt text='Pay with QuickPay'}</button>
                                <div style="display:none;margin-right:90px" id="redirecting" class="right">
                                    <img src="{$baseurl}modules/ZSELEX/images/ajax-loading.gif">
                                </div>
                            </form>
                            {elseif $paytype eq 'epay'}
                            <form id="payforms"  action="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/Default.aspx" method="post">

                                <input type="hidden" name="merchantnumber" value="{$epayForm.merchant_number}">
                                <input type="hidden" name="orderid" value="{$epayForm.ordernumber}"> 
                                <input type="hidden" id="ep_amount" name="amount" value="{$epayForm.amount}"> 
                                <input type="hidden" name="currency" value="{$epayForm.currency}">
                                <input type="hidden" name="windowstate" value="{$epayForm.windowstate}"> 
                                <input type="hidden" name="instantcallback" value="{$epayForm.instantcallback}"> 
                                <input type="hidden" name="ownreceipt" value="{$epayForm.ownreceipt}"> 
                                <input type="hidden" name="callbackurl" value="{$epayForm.callbackurl}"> 
                                <input type="hidden" name="ordertext" value="{$epayForm.ordertext}"> 
                                <input type="hidden" name="description" value="{$epayForm.description}"> 
                                {if $epayForm.set_hash}
                                <input type="hidden" name="hash" value="{$epayForm.hash}"> 
                                {/if}
                                <input type="hidden" name="accepturl" value="{$epayForm.accepturl}"> 
                                <input type="hidden" name="cancelurl" value="{$epayForm.cancelurl}"> 
                                <button class="btn btn-primary checkout-btn paybtns ep_button" type="button">{gt text='Pay with ePay'}{if $epay_info.test_mode}&nbsp;({gt text='test mode'}){/if}</button>
                                <div style="display:none;margin-right:90px" id="redirecting" class="right">
                                    <img src="{$baseurl}modules/ZSELEX/images/ajax-loading.gif">
                                </div>
                            </form>
                            {elseif $paytype eq 'printorder'}
                            <form action='{modurl modname="ZSELEX" type="user" func="orderConfirmation" theme="printer"}' method='post'>
                                <button type="submit" class="btn btn-primary checkout-btn pp_paybtns">{gt text='Print Order'}<span class="Right_Arrow"></span></button>
                            </form>
                            {/if}
                    </td>
                </tr>
            </tfoot>
        </table>
                     <input type="hidden" id="deleveryUrl" value="{$baseurl}{modurl modname="ZSELEX" type="user" func="paymentoptions" shop_id=$shop_id}">


        {*</div>*}

    <!-- END CART WRAP 01 -->


</div>
</div>