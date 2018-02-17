{*<h3>Thank you for your payment.<h3>

<br>

<b>Your Reciept :</b>
<br><br>

{$reciept}
*}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/checkout.css"} 

{ajaxheader imageviewer="true"}

     {insert name='getstatusmsg'}
        <style type="text/css">
            .Hd{ height:31px; background: #f2f2f2; border:1px #f2f2f2 solid; font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:12px; }
            .Hd div{height:21px;  float:left; margin-top:7px; margin-left:3px}
            .TblRow div{height:80px;  float:left; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding-top:7px; padding-left:6px; border: solid 1px #f2f2f2; border-left:none; border-top:none}
			.CartTable td{ padding:3px 10px; vertical-align:center}
        </style>
  

                    <div class="checkout_form">
        <h2>{gt text='Order Summary'}</h2>
        
<h3>Your Order Id : {$order_id}</h3>
{gt text='Congratulations on your order has gone through - you will receive an order confirmation by email. If you have not received it within 10 minutes, please contact us'}
  
{if $payment_type eq 'directpay'}
    <br><br>
     {$directpay.info}
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
                        <td width="100px"><b>From Seller&nbsp;&nbsp;:</b></td>
                        <td><b>{$owner_name}</b></td>
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
               {foreach  from=$items  key=k1 item=v1}  
                   
                <tr class="TblRow">
                    <td>
                    	<center><img src="zselexdata/{$owner_name}/products/thumb/{$v1.prd_image}" width="70" /><br><p><b>{$v1.product_name}</b></p></center></td>
                    <td>{$v1.prd_description}</td>
                    <td>DKK {displayprice amount=$v1.price}</td>
                   
                    <td>{$v1.quantity}</td>
                    <td>DKK {displayprice amount=$v1.total}</td>
                   
                </tr>
                  
                  {/foreach}
                    
               </form>
                       
                    <tr class="Hd">
                    <td style="width:515px; text-align:right; font-weight:bold" colspan="4">
                        {gt text='Shipping'}:<br />{if $vat > 1}{gt text='Vat'}:<br />{/if}{gt text='Grand Total'}:
                    </td>
                    <td style="width:125px;color:red; font-weight:bold" colspan="2">
                       {if $shipping < 1}{gt text='Not Applicable'}{else}{$shipping}{/if}<br />{if $vat > 1}DKK {displayprice amount=$vat}<br />{/if}DKK {displayprice amount=$grand_total_all}
                    </td>
                    </tr>
              
</table>
                
             
        </div>


<div class="ButtonDiv left">
      <a href="{homepage}">
   <button type="submit" class="Orange_button">
       <span class="Left_Arrow"></span>{gt text='Continue Shopping'}
   </button>
      </a>
   </div>
   </div>
</div>
 
   <style>
   .HalfSec{width:60%;}
   </style>