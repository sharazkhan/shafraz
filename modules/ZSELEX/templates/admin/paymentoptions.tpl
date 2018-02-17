
{adminheader}
{include file="admin/orderlinks.tpl"}
<form id="payoptions" class="z-form" action="{modurl modname="ZSELEX" type="admin" func="serviceOrder"}" method="post" enctype="application/x-www-form-urlencoded">

    <table>
    <thead>
       
      
    <th>{gt text='Select Payment Method'}</th>
      
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
      {if $modvars.ZPayment.Paypal_enabled}
    <tr>
        <td>
           {gt text="Paypal"}
        </td>
        <td>
          
              <input type="radio" name="paytype" id="field6-pp" value="paypal" checked/>
        </td>
    </tr>
    {/if}
    <tr>
        <td></td>
        <td></td>
    </tr>
     <tr>
        <td></td>
        <td></td>
    </tr>
     <tr>
        <td></td>
        <td></td>
    </tr>
    {if $modvars.ZPayment.Netaxept_enabled}
    <tr>
        <td>{gt text="Nets"}</td>
        <td>
         
             <input type="radio" name="paytype" id="field6-na" value="netaxept"  />
        </td>
    </tr>
    {/if}
    
    <tr>
        <td></td>
        <td></td>
    </tr>
     <tr>
        <td></td>
        <td></td>
    </tr>
     <tr>
        <td></td>
        <td></td>
    </tr>
    {if $modvars.ZPayment.QuickPay_enabled}
    <tr>
        <td>{gt text="QuickPay"}</td>
        <td>
         
             <input type="radio" name="paytype" id="field6-na" value="quickpay"  />
        </td>
    </tr>
    {/if}
      <tr>
        <td></td>
        <td></td>
    </tr>
     <tr>
        <td></td>
        <td></td>
    </tr>
     <tr>
        <td></td>
        <td></td>
    </tr>
     {if $modvars.ZPayment.Epay_enabled}
    <tr>
        <td>{gt text="ePay"}</td>
        <td>
         
             <input type="radio" name="paytype" id="field6-na" value="epay"  />
        </td>
    </tr>
    {/if}
    <tr>
        <td></td>
        <td></td>
    </tr>
     <tr>
        <td></td>
        <td></td>
    </tr>
     <tr>
        <td></td>
        <td></td>
    </tr>
    
   
     <tr>
        
        <td>
            {*<input type="submit" name="placeorder" value="place an order">*}
            <button type="submit" class="Orange_button right">{gt text='Place Order'}<span class="Right_Arrow"></span></button>
        </td>
    </tr>
    </thead>

</table>
      
</form>
 {adminfooter}        