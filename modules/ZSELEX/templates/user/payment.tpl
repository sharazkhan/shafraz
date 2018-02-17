 {*
<div align="right">

    <form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' name='payapl'>
        <input type="hidden" name="cmd" value="_xclick" />
        <input type="hidden" name="business" value="seller_1357558142_biz@.com" />
        <input type='hidden' name='quanity' value='1' />
        <input type='hidden' name='order_id' value="{$order_id}"/>
        <input type='hidden' name='item_number' value="{$order_id}">
        <input type="hidden" name="amount" value={$totalprice} />
        <input type="hidden" name="item_name" value="test order description" />
        <input type="hidden" name="billing_cust_name" value="Sharaz Khan YA">
        <input type="hidden" name="billing_cust_address" value="Cochin 1">          
        <input type="hidden" name="billing_cust_tel" value="7760916387">
        <input type="hidden" name="billing_cust_email" value="sharazkhanz@gmail.com">                                              

        <input type="hidden" name="custom" value="" />

        <input type="hidden" name="return" value="{$baseurl}payPalReturn/order_id/{$order_id}" />
        <input type="hidden" name="cancel_return" value="{$baseurl}order/id/104/order_id/{$order_id}/action/cancelled" />
        <input type="image" name="submit "border="0" 
        src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" 
        alt="PayPal - The safer, easier way to pay online" /> 
    </form>

</div>
*}


<form method="post" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
<input type="hidden" value="_cart" name="cmd">
<input type="hidden" value="1" name="upload">
<input type="hidden" value="seller_1357558142_biz@.com" name="business">


 {foreach from=$products key=k item=product}
 
<input type="hidden" value="{$product.PRODUCTNAME}" name="item_name_{$k+1}">
<input type="hidden" value="{$product.PRODUCTID}" name="item_number_{$k+1}">
<input type="hidden" value="{$product.FINALPRICE}" name="amount_{$k+1}">
<input type="hidden" value="{$product.QUANTITY}" name="quantity_{$k+1}">
<input type="hidden" value="1" name="weight_{$k}">
    
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

<input type="hidden" value="USD" name="currency_code">
<input type="hidden" value="sharaz" name="first_name">
<input type="hidden" value="khan" name="last_name">
<input type="hidden" value="fort kochi" name="address1">
<input type="hidden" value="bishop garden" name="address2">
<input type="hidden" value="cochin" name="city">
<input type="hidden" value="kerala" name="state">
<input type="hidden" value="682001" name="zip">
<input type="hidden" value="IN" name="country">
<input type="hidden" value="0" name="address_override">
<input type="hidden" value="sharazkhanz@gmail.com" name="email">
<input type="hidden" value="8 - sharaz khan" name="invoice">
<input type="hidden" value="en" name="lc">
<input type="hidden" value="2" name="rm">
<input type="hidden" value="1" name="no_note">
<input type="hidden" value="utf-8" name="charset">
<input type="hidden" value="{$baseurl}payPalReturn/order_id/{$order_id}" name="return">
<!--<input type="hidden" value="http://localhost/opencart/upload/index.php?route=payment/pp_standard/callback" name="notify_url">-->
<input type="hidden" value="{$baseurl}order/id/104/order_id/{$order_id}/action/cancelled" name="cancel_return">
<input type="hidden" value="authorization" name="paymentaction">
<input type="hidden" value="8" name="custom">

 <input type="image" name="submit "border="0" 
        src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" 
        alt="PayPal - The safer, easier way to pay online" /> 
</form>
