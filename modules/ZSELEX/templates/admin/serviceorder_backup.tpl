{adminheader}
<h3>{gt text="Order Summary"}</h3>

{ajaxheader imageviewer="true"}

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
                        {$item.plugin_name}
                    </td>
                    <td>{$item.shop_name}</td>
                    <td>{$item.quantity}</td>
                    <td>{$item.price}</td>
                    <td>{$item.subtotal_upgraded}</td>
                   
                </tr>
                  
                  {/foreach}
                    
               </form>
                   
                 <tr class="Hd">
                    <td style="width:515px; text-align:right; font-weight:bold" colspan="4">{gt text="Grand Total"}:</td>
                    <td style="width:125px;color:red; font-weight:bold" colspan="2">{$granTotal}</td>
                    </tr>
                    <form name='co' action='index.php?module=zselex&type=admin&func=confirmServiceOrder' method='post'>
                    <tr>
                        <td  colspan="5" align="right"><input type="submit" name="updatecart" value="{gt text='Confirm Order'}"></td> 
                    </tr>
                    </form>
              
</table>
            <div></div>     
             
        </div>
                    
                    
        {adminfooter}           