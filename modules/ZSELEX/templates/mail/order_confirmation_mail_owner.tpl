
{pageaddvar name="stylesheet" value="themes/CityPilot/style/checkout.css"} 
{mailcss}
     {insert name='getstatusmsg'}
        <style type="text/css">
            .Hd{ height:31px; background: #f2f2f2; border:1px #f2f2f2 solid; font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:12px; }
            .Hd div{height:21px;  float:left; margin-top:7px; margin-left:3px}
            .TblRow div{height:80px;  float:left; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding-top:7px; padding-left:6px; border: solid 1px #f2f2f2; border-left:none; border-top:none}
			.CartTable td{ padding:3px 10px; vertical-align:center}
        </style>
        
  <table {$headerTable}>
    <tr>
        <td  {$header1stTd}>
            
              <a href="http://citypilot.dk/index.php"><img style="margin-top: 24px;" src="{$themepath}/images/Logo.png" class="logo" /></a>
        </td>
    
        <td {$header2ndTd}>
             <h3 {$headerH3}>
                 
                    {$subject}
                </h3>
                
        </td>
    </tr>
</table>
  

 <div class="checkout_form" style="margin-top: 20px !important;margin-bottom: 20px !important;padding-left: 50px !important;font-size:14px !important;color:#666666 !important;">
 <h2>{gt text='Order Summary'}</h2>
        
<h3>{gt text='Order Id'} : {$order_id}</h3>

<h4>{gt text='Payment Method'} : {$payment_method}</h4>  
{if $payment_mode.test_mode}
<h4>{gt text='Payment Mode'} : Test </h4>
{/if}
{if $cardtype neq ''}
<h4>{gt text='Card Type'} : {$cardtype}</h4> 
{/if}
<h3><u>{gt text='Customer Details'}</u> :</h3> 
<div><b>{gt text='Customer Name'} : </b> &nbsp; {$order_info.first_name}&nbsp;{$order_info.last_name}</div>
<div><b>{gt text='Email'} : </b> &nbsp; {$order_info.email}</div>
<div><b>{gt text='Delivery Address'} : </b> &nbsp; {$order_info.address}</div>
<div><b>{gt text='ZIP code. city'} : </b> &nbsp; {$order_info.zip} {$order_info.city}</div>
{*<div><b>{gt text='City'} : </b> &nbsp; {$order_info.city}</div>*}

<div><b>{gt text='Phone Number'} : </b> &nbsp; {$order_info.phone}</div>
{if $payment_type eq 'directpay'}
    <br><br>
    {$checkout_info.info}
 {/if}   
    <div style="width:100%; margin:auto;">
                <table style="border-collapse:collapse; width:100%; border:solid 1px #f2f2f2;" class="CartTable">
                 <form name='updatecart{$k}' action='index.php?module=zselex&type=user&func=updatecart&shop_id={$k}' method='post'>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                     <tr>
                        <td width="100px"><b>{gt text='Shop'}&nbsp;&nbsp;:&nbsp;&nbsp;{$shop_name}</b></td>
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
               {foreach from=$items  key=k1 item=v1}  
                   
                <tr class="TblRow">
                    <td>
                    	<center>
                            <a href="{$baseurl}{modurl modname="ZSELEX" type="user" func="productview" id=$v1.product_id}">
          {assign var="image1" value=$v1.prd_image|replace:' ':'%20'}
          {assign var="image" value="zselexdata/`$shop_id`/products/thumb/`$image1`"}
  {if is_file($image)}
   <img src="{$baseurl}zselexdata/{$shop_id}/products/thumb/{$v1.prd_image}" width="70" />

<br>
{/if}
<p><b>{$v1.product_name}</b></p>
</a>
                        </center>
                
               </td>
                    <td>
                        {$v1.prd_description}
                         {if $v1.product_options!='' OR $v1.product_options!='[]'}
                             <br>
                          {displayoptions options=$v1.product_options}
                        {/if}
                         {if $v1.prd_answer!=''}
                            <div style="float:none; display: block; border:none; height: auto">
                                <b>{$v1.prd_question}:</b><br>
                            {$v1.prd_answer}
                            </div>
                         {/if}
                         {if $v1.no_vat}
                             
                             <p>*</p>
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
                       {if $shipping < 1}{gt text='Free'}{else}DKK {displayprice amount=$shipping}{/if}<br />{if $vat > 0}DKK {displayprice amount=$vat}<br />{/if}DKK {displayprice amount=$grand_total_all}
                    </td>
                    </tr>
              
</table>
                
             
        </div>
                    
                   {if $hasNoVatProduct}
                    <div>* {gt text='VAT is not applicable for this product'}</div>  
                    {/if}
   </div>
                    
       <table  {$footerTable}>
    <tr>
        <td {$footerTd}>
                <img src="{$themepath}/images/FooterLogo.png"  {$footerLogo}/> <img src="{$themepath}/images/bg.png"  />
        </td>
     </tr>
     
</table>
 
   <style>
   .HalfSec{ width:60%;}
   </style>