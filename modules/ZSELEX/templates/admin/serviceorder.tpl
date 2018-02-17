{adminheader}
{include file="admin/orderlinks.tpl"}
<h3>{gt text="Order Summary"}</h3>



{insert name='getstatusmsg'}
<style type="text/css">
    .Hd{ height:31px; background: #f2f2f2; border:1px #f2f2f2 solid; font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:12px; }
    .Hd div{height:21px;  float:left; margin-top:7px; margin-left:3px}
    .TblRow div{height:80px;  float:left; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding-top:7px; padding-left:6px; border: solid 1px #f2f2f2; border-left:none; border-top:none}
    .CartTable td{ padding:3px 10px; vertical-align:center}
</style>

<div style="width:100%; margin:auto;">
    <table style="border-collapse:collapse; width:100%; border:solid 1px #f2f2f2;" class="CartTable">
        <form name='updatecart{$k}' action='index.php?module=zselex&type=user&func=updatecart&shop_id={$k}' method='post'>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            <tr class="Hd">
                <td>{gt text='Service'}</td>
                <td>{gt text='Shop'}</td>
                <td>{gt text='Quantity'}</td>
                <td>{gt text='Price'}</td>
                <td>{gt text='Subtotal'}</td>
                <td>&nbsp;</td>
            </tr>
            <!--table content Row -->
            {foreach  from=$cart  key=k1 item=item}  

            <tr class="TblRow">
                <td>
                    {*{$item.type}*}
                    {$item.bundle_name}
                </td>
                <td>{$item.shop_name}</td>
                <td>{$item.quantity}</td>
                <td>{displayprice amount=$item.price}</td>
                <td>{displayprice amount=$item.subtotal_upgraded}</td>

            </tr>

            {/foreach}

        </form>

        <tr class="Hd">
            <td style="width:515px; text-align:right; font-weight:bold" colspan="4">{gt text="Grand Total"}:</td>
            <td style="width:125px;color:red; font-weight:bold" colspan="2">{displayprice amount=$granTotal}</td>
        </tr>
        <tr>
            <td  colspan="5" align="right">
                {*  <form name='co' action='index.php?module=zselex&type=admin&func=confirmServiceOrder' method='post'> *}
                    {if $payment_method eq 'paypal'}
                    {if $test_mode}
                    <form method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
                        {else}
                        <form method="post" action="https://www.paypal.com/cgi-bin/webscr">
                            {/if}     
                            <input type="hidden" value="_cart" name="cmd">
                            <input type="hidden" value="1" name="upload">


                            {*<input type="hidden" value="r2internation-facilitator@india.com" name="business">*}
                            <input type="hidden" value="{$paypal_business_email}" name="business">

                            {foreach from=$cart key=k item=item}

                            <input type="hidden" value="{$item.bundle_name}" name="item_name_{$k+1}">
                            <input type="hidden" value="{$item.bundle_id}" name="item_number_{$k+1}">
                            <input type="hidden" value="{$item.price|number_format:2}" name="amount_{$k+1}">
                            <input type="hidden" value="{$item.quantity}" name="quantity_{$k+1}">
                            <input type="hidden" value="1" name="weight_{$k}">

                            {/foreach}
                            {* {assign var="order_id" value="asdd000876506"}
                            {$order_id} *}
                            <input type="hidden" value="DKK" name="currency_code">
                            <input type="hidden" value="{$user_info.first_name}" name="first_name">
                            <input type="hidden" value="{$user_info.last_name}" name="last_name">
                            <input type="hidden" value="{$user_info.address}" name="address1">
                            <input type="hidden" value="" name="address2">
                            <input type="hidden" value="{$user_info.city}" name="city">
                            <input type="hidden" value="{$user_info.state}" name="state">
                            <input type="hidden" value="{$user_info.zip}" name="zip">
                            <input type="hidden" value="{$user_info.country}" name="country">
                            <input type="hidden" value="0" name="address_override">
                            <input type="hidden" value="{$user_info.email}" name="email">
                            {*  <input type="text" value="{$order_id} - sharaz khan" name="invoice">*}
                            <input type="hidden" value="{$order_id}" name="invoice">
                            <input type="hidden" value="en" name="lc">
                            <input type="hidden" value="2" name="rm">
                            <input type="hidden" value="1" name="no_note">
                            <input type="hidden" value="utf-8" name="charset">
                            <input type="hidden" value="{$baseurl}index.php?module=zselex&type=admin&func=payPalReturnServicePaid&order_id={$order_id}" name="return">
                            <!--<input type="hidden" value="http://localhost/opencart/upload/index.php?route=payment/pp_standard/callback" name="notify_url">-->
                            <input type="hidden" value="{$baseurl}index.php?module=zselex&type=admin&func=paypalServiceOrderCancel&order_id={$order_id}" name="cancel_return">
                            <input type="hidden" value="authorization" name="paymentaction">
                            <input type="hidden" value="{$shop_id}" name="custom">
                            <input type="image" name="submit "border="0" 
                                   src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" 
                                   alt="{gt text='PayPal - The safer, easier way to pay online'}" /> 

                        </form>
                        {elseif $payment_method eq 'netaxept'}
                        <form action='{$netaxept.terminal_url}' method='post'>
                            <input class="right" type="submit" name="submit" value="{gt text='Pay with Netaxept'}">
                        </form>


                        {elseif $payment_method eq 'quickpay'}
                        {*<form action="https://secure.quickpay.dk/form/" method="post">
                            <input type="hidden" name="protocol" value="{$quickpay_info.protocol}" />
                            <input type="hidden" name="msgtype" value="{$quickpay_info.msgtype}" />

                            {if $quickpay_info.test_mode eq '1'}
                            <input type="hidden" name="testmode" value="1" />
                            {/if}

                            <input type="hidden" name="merchant" value="{$quickpay_info.merchant}" />
                            <input type="hidden" name="language" value="{$quickpay_info.language}" />
                            <input type="hidden" name="ordernumber" value="{$quickpay_info.ordernumber}" />
                            <input type="hidden" name="amount" value="{$quickpay_info.amount}" />
                            <input type="hidden" name="currency" value="{$quickpay_info.currency}" />
                            <input type="hidden" name="continueurl" value="{$quickpay_info.continueurl}" />
                            <input type="hidden" name="cancelurl" value="{$quickpay_info.cancelurl}" />
                            <input type="hidden" name="callbackurl" value="{$quickpay_info.callbackurl}" />
                            <input type="hidden" name="autocapture" value="{$quickpay_info.autocapture}" />
                            <input type="hidden" name="md5check" value="{$quickpay_info.md5check}" />

                            <input class="right" type="submit" value="{gt text='Pay with QuickPay'}" />
                        </form>*}
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
                            <input type="{$text_type}" name="variables[CUSTOM_user_id]" value="{$user_id}" />
                            <input type="{$text_type}" name="checksum" value="{$checksum}">

                        </form>

                        {elseif $payment_method eq 'epay'}
                        <form id="payforms"  action="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/Default.aspx" method="post">
                            <input type="hidden" name="merchantnumber" value="{$epayForm.merchant_number}">
                            <input type="hidden" name="orderid" value="{$epayForm.ordernumber}"> 
                            <input type="hidden" name="amount" value="{$epayForm.amount}"> 
                            <input type="hidden" name="currency" value="{$epayForm.currency}">
                            <input type="hidden" name="windowstate" value="{$epayForm.windowstate}"> 
                            <input type="hidden" name="instantcallback" value="{$epayForm.instantcallback}"> 
                            <input type="hidden" name="ownreceipt" value="{$epayForm.ownreceipt}"> 
                            {* <input type="hidden" name="callbackurl" value="{$epayForm.callbackurl}"> *}
                            {if $epayForm.set_hash}
                            <input type="hidden" name="hash" value="{$epayForm.hash}"> 
                            {/if}
                            <input type="hidden" name="accepturl" value="{$epayForm.accepturl}"> 
                            <input type="hidden" name="cancelurl" value="{$epayForm.cancelurl}"> 
                            <input class="right" type="submit" value="{gt text='Pay with Epay'}" />
                        </form>
                        {/if}
                        </td> 
                        </tr>


                        </table>
                        <div></div>     

                        </div>


                        {adminfooter}           