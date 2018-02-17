<input type="hidden" name="limitshops" value="{$vars.amount}" id="limitshops">

{* Purpose of this template: Display shops within an external context *}

{*
<dt>


<div id="blockshopscontent"  style="width:400px; height:auto; display:table-cell;">
    <dl>
   
        {foreach item='item' from=$shops}
       
        <div style="float:left; width:340px; font-size:20px; margin-left:20px; border:1px solid black">
    <table  cellpadding="10" cellspacing="10" style="float:left; width:340px; font-size:20px;" >
    <tr>
         <td><span class='phd1'>Shop Name : </span></td>
        <td><span class='phd1'>{$item.shop_name}</span></td>
    </tr>
    <tr>
         <td><span class='phd1'>Description : </span></td>
        <td><span class='phd1'>{$item.description}</span></td>
    </tr>
    <tr>
         <td><span class='phd1'>Address : </span></td>
        <td><span class='phd1'>{$item.address}</span></td>
    </tr>
    <tr>
         <td><span class='phd1'>Phone number : </span></td>
        <td><span class='phd1'>{$item.telephone}</span></td>
    </tr>
    <tr>
         <td><span class='phd1'>Email : </span></td>
        <td><span class='phd1'>{$item.email}</span></td>
    </tr>
    
</table>
          
        </div>

        {foreachelse}
       <dt> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gt text='No entries found.'}</dt>

        {/foreach}
       

    </dl>

   

</div> *}

<input type='hidden' id='shopadtype' value='{$vars.adtype}'>

<div id="shops_ad"  style="width:400px;  height:auto; display:table-cell; margin-bottom:5px;">
   <dt> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gt text='No entries found.'}</dt>

{*{foreach item='item' from=$shops}
<div style='border:solid 1px #CCC; padding-left:15px; padding-top:15px; padding-bottom:5px'> 
              
                      <div><b>Shop Name</b>:  {$item.shop_name} </div>
                          
                      <div><b>Address</b>: {$item.address}</div>
                          
                      <div><b>Telephone</b>: {$item.telephone}</div>
                       
                      <div><b>Fax</b>: {$item.fax}</div>
                          
                      <div><b>Email</b>: {$item.email} </div>

                   </div>
    
{/foreach}*}
</div>









