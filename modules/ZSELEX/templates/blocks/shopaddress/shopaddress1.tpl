{*<div id="shopaddress" style="background-color:#e65622;  width:200px; color:#FFF; float: right; padding: 10px">*}

 {if $perm} 
     <div class="OrageEditSec"><a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}#aAddress"><img src="themes/{$current_theme}/images/OrageEdit.png">{gt text='Edit Content'}</a></div>
     {/if}

<div id="shopaddress" style="background-color:#e65622;  width:200px; color:#FFF;   padding: 10px">
{assign var="time" value=$shopinfo.opening_hours|unserialize}
{if $time.mon.open neq '' || $time.tue.open neq '' || $time.wed.open neq '' || $time.thu.open neq '' || $time.fri.open neq '' || $time.sat.open neq '' || $time.sun.open neq '' || $time.comment neq ''}
<div><strong>{gt text='Opening Hours'}</strong></div>
<div>
{*    
Mandag - onsdag 10.00 - 17.30
<br>
Torsdag - fredag 10.00 - 19.00
<br>
Lorgag 10.00 - 16.00
*}
{*{$shopinfo.opening_hours|nl2table}*}

<table>
     <!--tr>
        <td></td>
        <td>{gt text='Open'}</td>
        <td></td>
        <td>{gt text='Close'}</td>
    </tr-->
   {if $time.mon.open neq '' || $time.mon.close neq ''}
     <tr>
        <td>{gt text='Monday'}</td>
        {if $time.mon.closed}
        <td>{gt text='Closed'}</td>
        {else}  
        <td>{$time.mon.open}</td>
        <td>-</td>
        <td>{$time.mon.close}</td>
        <td width="50%"></td>
        {/if}
    </tr>
    {/if}
    {if $time.tue.open neq '' || $time.tue.close neq ''}
    <tr>
        <td>{gt text='Tuesday'}</td>
        {if $time.tue.closed}
        <td>{gt text='Closed'}</td>
        {else} 
        <td>{$time.tue.open}</td>
        <td>-</td>
        <td>{$time.tue.close}</td>
        <td width="50%"></td>
        {/if}
    </tr>
     {/if}
      {if $time.wed.open neq '' || $time.wed.close neq ''}
    <tr>
        <td>{gt text='Wednesday'}</td>
        {if $time.wed.closed}
        <td>{gt text='Closed'}</td>
        {else} 
        <td>{$time.wed.open}</td>
        <td>-</td>
        <td>{$time.wed.close}</td>
        <td width="50%"></td>
        {/if}
    </tr>
     {/if}
    {if $time.thu.open neq '' || $time.thu.close neq ''}
    <tr>
        <td>{gt text='Thursday'}</td>
        {if $time.thu.closed}
        <td>{gt text='Closed'}</td>
        {else} 
        <td>{$time.thu.open}</td>
        <td>-</td>
        <td>{$time.thu.close}</td>
        <td width="50%"></td>
        {/if}
    </tr>
     {/if}
     {if $time.fri.open neq '' || $time.fri.close neq ''}
    <tr>
        <td>{gt text='Friday'}</td>
        {if $time.fri.closed}
        <td>{gt text='Closed'}</td>
        {else} 
        <td>{$time.fri.open}</td>
        <td>-</td>
        <td>{$time.fri.close}</td>
        <td width="50%"></td>
        {/if}
    </tr>
     {/if}
      {if $time.sat.open neq '' || $time.sat.close neq ''}
     <tr>
        <td>{gt text='Saturday'}</td>
         {if $time.sat.closed}
        <td>{gt text='Closed'}</td>
        {else}   
        <td>{$time.sat.open}</td>
        <td>-</td>
        <td>{$time.sat.close}</td>
        <td width="50%"></td>
        {/if}
    </tr>
       {/if}
     {if $time.sun.open neq '' || $time.sun.close neq ''}
     <tr>
        <td>{gt text='Sunday'}</td>
        {if $time.sun.closed}
        <td>{gt text='Closed'}</td>
        {else}   
        <td>{$time.sun.open}</td>
        <td>-</td>
        <td>{$time.sun.close}</td>
        <td width="50%"></td>
        {/if}
    </tr>
      {/if}
     {if $time.comment neq ''}
     <tr>
        <td colspan="5">
            {*{$time.comment|nl2br|wordwrap:35:"<br/>":true}*}
            {$time.comment|nl2br}
        </td>
    </tr>
      {/if}
</table>


</div>
 <br>
 {/if}
<div><strong>{gt text='Contact Us'}</strong></div>
<!--<div>{$shopinfo.address|wordwrap:30:"<br/>"}</div>-->
{if $shopinfo.address neq ''}
<div>{$shopinfo.address|nl2br}</div>
{/if}
{if $shopinfo.email neq ''}
<div>{gt text='Email'} : {$shopinfo.email}</div>
{/if}
{if !empty($shopinfo.fax)}
<div>{gt text='Fax'}: {$shopinfo.fax}</div>
{/if}
{if $shopinfo.telephone neq ''}
<div>{gt text='Tel'} : {$shopinfo.telephone}</div>
{/if}
{if $shopinfo.vat_number neq ''}
<div>{gt text='VAT#'} : {$shopinfo.vat_number|wordwrap:30:"<br/>":true}</div>
{/if}
</div>        
        
 <script>
        jQuery("#shopaddress").prevAll('h4:first').css("display", "none");
 </script>
 <div id="" style="height:2px"></div>