{*<div id="shopaddress" style="background-color:#e65622;  width:200px; color:#FFF; float: right; padding: 10px">*}
 {if $perm} 
     <div class="OrageEditSec EditAddress"><a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}"><img src="themes/CityPilot/images/OrageEdit.png">{gt text='Edit Content'}</a></div>
     {/if}

{if $shopinfo.opening_hours neq ''}  
<div class="skinColorOval" id="shopaddress">
  <h3>{gt text='Opening Hours'}</h3>
  {*{$shopinfo.opening_hours|nl2table}*}
  {assign var="time" value=$shopinfo.opening_hours|unserialize}
<table>
    <tr>
        <td></td>
        <td>{gt text='Open'}</td>
        <td>{gt text='Close'}</td>
    </tr>
   {if $time.mon.open neq '' || $time.mon.close neq ''}
     <tr>
        <td>{gt text='Monday'}</td>
        <td>{$time.mon.open}</td>
        <td>{$time.mon.close}</td>
    </tr>
    {/if}
    {if $time.tue.open neq '' || $time.tue.close neq ''}
    <tr>
        <td>{gt text='Tuesday'}</td>
        <td>{$time.tue.open}</td>
        <td>{$time.tue.close}</td>
    </tr>
     {/if}
      {if $time.wed.open neq '' || $time.wed.close neq ''}
    <tr>
        <td>{gt text='Wednesday'}</td>
        <td>{$time.wed.open}</td>
        <td>{$time.wed.close}</td>
    </tr>
     {/if}
    {if $time.thu.open neq '' || $time.thu.close neq ''}
    <tr>
        <td>{gt text='Thursday'}</td>
        <td>{$time.thu.open}</td>
        <td>{$time.thu.close}</td>
    </tr>
     {/if}
     {if $time.fri.open neq '' || $time.fri.close neq ''}
    <tr>
        <td>{gt text='Friday'}</td>
        <td>{$time.fri.open}</td>
        <td>{$time.fri.close}</td>
    </tr>
     {/if}
      {if $time.sat.open neq '' || $time.sat.close neq ''}
     <tr>
        <td>{gt text='Saturday'}</td>
        <td>{$time.sat.open}</td>
        <td>{$time.sat.close}</td>
    </tr>
       {/if}
     {if $time.sun.open neq '' || $time.sun.close neq ''}
     <tr>
        <td>{gt text='Sunday'}</td>
        <td>{$time.sun.open}</td>
        <td>{$time.sun.close}</td>
    </tr>
      {/if}
</table>

</div> 
 {/if}

<div class="skinColorOval" id="shopaddress">
<h3>{gt text='Contact Us'}</h3>
<!--<div>{$shopinfo.address|wordwrap:30:"<br/>"}</div>-->
<p>{$shopinfo.address|nl2br}</p>
<p>Email : {$shopinfo.email}</p>
<p>Fax: {$shopinfo.fax}</p>
<p>Tel : {$shopinfo.telephone}</p>
</div>  

        
 <script>
        jQuery("#shopaddress").prevAll('h4:first').css("display", "none");
 </script>
        