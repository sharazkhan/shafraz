{pageaddvar name="stylesheet" value="themes/CityPilot/style/checkout.css"} 
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/order.js'}

{include file="user/cartmenu.tpl"}<br><br>
<div class="checkout_form">
    <h2>{gt text='Order Summary'}</h2>
</div>

{*{ajaxheader imageviewer="true"}*}
{insert name='getstatusmsg'}
<style type="text/css">
    .Hd{ height:31px; background: #f2f2f2; border:1px #f2f2f2 solid; font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:12px; }
    .Hd div{height:21px;  float:left; margin-top:7px; margin-left:3px}
    .TblRow div{height:80px;  float:left; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding-top:7px; padding-left:6px; border: solid 1px #f2f2f2; border-left:none; border-top:none}
    .CartTable td{ padding:3px 10px; vertical-align:center}
</style>

<div style="width:100%; margin:auto;">
    {*
    {setsellernameorder value=$products shop_id=$shop_id} 
    *}  
    <table style="border-collapse:collapse; width:100%; border:solid 1px #f2f2f2;" class="CartTable">
        <form name='updatecart{$k}' action='index.php?module=zselex&type=user&func=updatecart&shop_id={$k}' method='post'>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td width="140px"><b>{gt text='Shop'}&nbsp;&nbsp;: &nbsp;&nbsp;{$shop_name}</b></td>
                <td></td>
            </tr>
            <tr class="Hd">
                <td>{gt text='Item'}</td>
                <td>{gt text='Description'}</td>
                <td>{gt text='Price'}</td>
                <td>{gt text='Quantity'}</td>
                <td>{gt text='Subtotal'}</td>
                <td>&nbsp;</td>
            </tr>
            <!--table content Row -->
            {foreach  from=$products  key=k1 item=v1}  
            <tr class="TblRow">
                <td>
            <center>
                <a href="{modurl modname="ZSELEX" type="user" func="productview" id=$v1.product_id}">
                   <img src="zselexdata/{$shop_id}/products/thumb/{$v1.prd_image}" width="70" /><br><p><b>{$v1.product_name}</b></p>
                </a>
            </center>
            </td>
            <td>
                {$v1.prd_description}
                {if $v1.product_options!='' OR $v1.product_options!='[]'}
                {displayoptions options=$v1.product_options}
                {/if}

                {if $v1.prd_answer!=''}
                <div style="float:none; display: block; border:none; height: auto">
                    <b>{$v1.prd_question}:</b><br>
                    {$v1.prd_answer}
                </div>
                {/if}
            </td>
            <td>DKK {displayprice amount=$v1.price}</td>
            <td>{$v1.quantity}</td>
            <td>DKK {displayprice amount=$v1.total}</td>
            </tr>
            {/foreach}
        </form>
        <tr class="Hd">
            <td style="width:515px; text-align:right; font-weight:bold" colspan="4">
                {gt text='Shipping'}:<br />{if $vat > 0}{gt text='Vat'}:<br />{/if}{gt text='Grand Total'}:
            </td>
            <td style="width:125px;color:red; font-weight:bold" colspan="2">
                {if $shippingVal < 1}{gt text='Free'}{else}DKK {displayprice amount=$shippingVal}{/if}<br />{if $vat > 0}DKK {displayprice amount=$vat}<br />{/if}DKK {displayprice amount=$grand_total_all}
            </td>
        </tr>
    </table>
</div>

<br>
<br>

{if $paytype eq 'paypal'}
{if $paypal_info.test_mode}
<form id="payforms" method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
    {else}
    <form id="payforms" method="post" action="https://www.paypal.com/cgi-bin/webscr">      
        {/if}    
        <input type="hidden" value="_cart" name="cmd">
        <input type="hidden" value="1" name="upload">

        {* <input type="hidden" value="r2internation-facilitator@india.com" name="business"> *}
        <input type="hidden" value="{$paypal_info.business_email}" name="business">
       <!--<input type="hidden" value="{$paypalemail}" name="business">-->
        {foreach from=$product_form key=k item=product}

        <input type="hidden" value="{$product.product_name}" name="item_name_{$k+1}">
        <input type="hidden" value="{$product.product_id}" name="item_number_{$k+1}">
        {* <input type="hidden" value="{$product.price+$product.options_price|number_format:2}" name="amount_{$k+1}">*}
        <input class="pp_amount" qty="{$product.quantity}" type="hidden" value="{$product.total/$product.quantity|number_format:2}" name="amount_{$k+1}">
        <input type="hidden" value="{$product.quantity}" name="quantity_{$k+1}">
        <input type="hidden" value="{if $product.extra}0{else}1{/if}" name="weight_{$k+1}">

        {/foreach}


        {*
        <input type="hidden" value="Delivery Date" name="on0_2">
        <input type="hidden" value="2011-04-22" name="os0_2">
        *}


        {*
        <input type="hidden" value="Shipping, Handling, Discounts & Taxes" name="item_name_4">
        <input type="hidden" value="" name="item_number_4">
        <input type="hidden" value="5.00" name="amount_4">
        <input type="hidden" value="1" name="quantity_4">
        <input type="hidden" value="0" name="weight_4">
        *}

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
        {* <input class="right" type="image" name="submit "border="0" 
                  src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" 
                  alt="PayPal - The safer, easier way to pay online" /> *}
        {* <input class="right paybtns" type="button" value="Pay with PayPal" />*}
        <button class="right pp_paybtns Orange_button right" type="button">{gt text='Pay with PayPal'}{if $paypal_info.test_mode}&nbsp;({gt text='test mode'}){/if}</button>
        <div style="display:none;margin-right:90px" id="redirecting" class="right">
            {*<b><font size="3" color="red">{gt text='Redirecting...'}</font></b>*}
            <img src="{$baseurl}modules/ZSELEX/images/ajax-loading.gif">
        </div>
    </form>

    {elseif $paytype eq 'netaxept'}    
    <form id="payforms" action='{$netaxept.terminal_url}' method='post'>

        <button class="right na_paybtns Orange_button right" type="button">{gt text='Pay with Netaxept'}{if $netaxept_info.test_mode}&nbsp;({gt text='test mode'}){/if}</button>
        <div style="display:none;margin-right:90px" id="redirecting" class="right">
            <img src="{$baseurl}modules/ZSELEX/images/ajax-loading.gif">
        </div>
    </form>
    <!---- QUICK PAY FORM -->
    {elseif $paytype eq 'quickpay'}
    <form id="payforms"  action="https://secure.quickpay.dk/form/" method="post">
        <input type="hidden" name="protocol" value="{$quickpay_info.protocol}" />
        <input type="hidden" name="msgtype" value="{$quickpay_info.msgtype}" />
        {if $quickpay_info.test_mode eq '1'}
        <input type="hidden" name="testmode" value="1" />
        {/if}
        <input type="hidden" name="merchant" value="{$quickpay_info.quickpay_id}" />
        <input type="hidden" name="language" value="{$quickpay_info.language}" />
        <input type="hidden" name="ordernumber" value="{$quickpay_info.ordernumber}" />
        <input type="hidden" id="qp_amount" name="amount" value="{$quickpay_info.amount}" />

        <input type="hidden" name="currency" value="{$quickpay_info.currency}" />
        <input type="hidden" name="continueurl" value="{$quickpay_info.continueurl}" />
        <input type="hidden" name="cancelurl" value="{$quickpay_info.cancelurl}" />
        <input type="hidden" name="callbackurl" value="{$quickpay_info.callbackurl}" />
        <input type="hidden" name="autocapture" value="{$quickpay_info.autocapture}" />
        {if $quickpay_info.pay_type eq 'individual'}
        <input type="hidden" name="splitpayment" value="{$quickpay_info.splitpayment}" />
        <input type="hidden" name="cardtypelock" value="{$quickpay_info.cardtypelock}" />
        {/if}
        <input type="hidden" name="description" value="{$quickpay_info.description}" />
        <input type="hidden" name="md5check" value="{$quickpay_info.md5check}" />
        <input type="hidden" name="CUSTOM_shop_id" value="{$shop_id}" />
        <input type="hidden" name="CUSTOM_user_id" value="{$user_id}" />
        <button class="right qp_button paybtns Orange_button right" type="button">{gt text='Pay with QuickPay'}{if $quickpay_info.test_mode}&nbsp;({gt text='test mode'}){/if}</button>
        <div style="display:none;margin-right:90px" id="redirecting" class="right">
            <img src="{$baseurl}modules/ZSELEX/images/ajax-loading.gif">
        </div>
    </form>
    <!---- EPAY FORM -->
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
        <button class="right paybtns ep_button Orange_button right" type="button">{gt text='Pay with ePay'}{if $epay_info.test_mode}&nbsp;({gt text='test mode'}){/if}</button>
        <div style="display:none;margin-right:90px" id="redirecting" class="right">
            <img src="{$baseurl}modules/ZSELEX/images/ajax-loading.gif">
        </div>
    </form>
    {elseif $paytype eq 'printorder'}
    <form action='{modurl modname="ZSELEX" type="user" func="orderConfirmation" theme="printer"}' method='post'>
        <button type="submit" class="Orange_button right">{gt text='Print Order'}<span class="Right_Arrow"></span></button>
    </form>
    {/if}
    
    <input type="hidden" id="deleveryUrl" value="{$baseurl}{modurl modname="ZSELEX" type="user" func="paymentoptions" shop_id=$shop_id}">
         