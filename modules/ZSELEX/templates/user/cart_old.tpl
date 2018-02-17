
     {insert name='getstatusmsg'}
        <style type="text/css">
            .Hd{ height:31px; background: #f2f2f2; border:1px #f2f2f2 solid; font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-size:12px; }
            .Hd div{height:21px;  float:left; margin-top:7px; margin-left:3px}
            .TblRow div{height:80px;  float:left; font-family:Arial, Helvetica, sans-serif; font-size:12px; padding-top:7px; padding-left:6px; border: solid 1px #f2f2f2; border-left:none; border-top:none}
			.CartTable td{ padding:3px 10px; vertical-align:center}
        </style>
  
        <div style="width:100%; margin:auto;">
                 <h2> CART </h2>
                <div>{if $check neq 0}<font color='red'>{gt text='This Item Is Already In Your Cart'}</font>{/if}</div>
                <div>{if $upcounter neq 0}<font color='green'>Your Cart Has Been Updated</font>{/if}</div>
                 
                <table style="border-collapse:collapse; width:100%; border:solid 1px #f2f2f2;" class="CartTable">
           {foreach  from=$products  key=k item=v}
                 {setsellername value=$products key=$k}
                  <form name='updatecart{$k}' action='index.php?module=zselex&type=user&func=updatecart&shop_id={$k}' method='post'>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><b>From Seller :</b> </td>
                    <td> <b>{$sellername}</b></td>
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
               {foreach  from=$products.$k  key=k1 item=v1}  
                    
                <tr class="TblRow">
                    <td>
                    	<center><img src="zselexdata/{$sellername}/products/thumb/{$v1.IMAGE}" width="70" /><br><p><b>{$v1.PRODUCTNAME}</b></p></center></td>
                    <td>{$v1.DESCRIPTION}</td>
                    <td>Rs. {$v1.REALPRICE}</td>
                    <td><input name="quantity[{$k1}]"  value='{$v1.QUANTITY}' size='3'></td>
                    <td>Rs. {$v1.FINALPRICE}</td>
                    <td><center><a href='index.php?module=zselex&type=user&func=deletecart&id={$k1}&shop_id={$k}'><img src="images/canel_btn.jpg" /></a></center></td>
                </tr>
                  
                  {/foreach}
                    <tr>
                    <td><input type="submit" value="{gt text='Update'}" /></td>
               </form>
                    <td align="right" >
                          <form name='checkout' action='{modurl modname="ZSELEX" type="user" func="checkout" shop_id=$k}' method='post'>
                        <input type="submit" value="{gt text='Check Out' comment=''}"/>
                         </form>
                    </td>
                    </tr>
                     <tr class="Hd">
                    <td style="width:515px; text-align:right; font-weight:bold" colspan="4">Shipping:<br />Grand Total:</td>
                    <td style="width:125px;color:red; font-weight:bold" colspan="2">Free<br />Rs. {$GRANDSUM}</td>
                    </tr>
                 
             {foreachelse}
                <tr class="TblRow">
                    <td style="width:655px; border-left: solid 1px #f2f2f2; padding-left:5px; font-weight:bold; padding-top:13px; height:30px; text-align: center" colspan="6" >No Item to Purchase</td>
                </tr>
                {/foreach}
                <!--End of table content Row -->
                {if $count neq 0}
                <!--Sum -->
                <!--
                <tr class="Hd">
                    <td style="width:515px; text-align:right; font-weight:bold" colspan="4">Shipping:<br />Grand Total:</td>
                    <td style="width:125px;color:red; font-weight:bold" colspan="2">Free<br />Rs. {$grandtotal}</td>
                </tr>
                -->
                <!-- End Sum -->

</table>
                
               <table class="CartTable" style="width:100%" >
                <tr>
                   <!-- <td><input type="submit" value="{gt text='Update'}" /></td> -->
                    {/if}
          
            {if $count neq 0}
            <td style="text-align:right">
                <form name='checkout' action='index.php?module=zselex&type=user&func=checkout' method='post'>
                    <!--<input type="submit" value="{gt text='Check Out' comment=''}"/>-->
                </form>
            </td>
            {/if}
        </tr>
        <!-- End Sum -->
        </table>
        </div>

    

