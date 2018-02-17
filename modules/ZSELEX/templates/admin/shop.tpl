  {shopheader}

  {ajaxheader imageviewer="true"}

<h1>{gt text='Shop Page'}</h1>
{*
<form id='newitemform' name='newitemform' action="{modurl modname='ZSELEX' type='user' func='newitem'}" method='post' target='_blank'>
    <input type="hidden" name="shop_id" value={$shopitem.shop_id}>
    <input type="hidden" name="shopName" value={$shopitem.shop_name}>
</form>    
<a href="javascript:document.newitemform.submit();">{gt text="submit article"}</a>
*}
{*
{if $perm}
<a href="{modurl modname='ZSELEX' type='user' func='newitem' shop_id=$shopitem.shop_id}">{gt text="submit news article"}</a>
{/if}
*}
<a href="{homepage}">{gt text="HOME"}</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

{if $notupdate_recent eq 1}
    <span>
    You have not updated in recent time&nbsp;&nbsp;&nbsp;<a href="{modurl modname='ZSELEX' type='admin' func='shopinnerview' shop_id=$smarty.request.shop_id op='cl'}">clear this</a>
    </span>
{/if} 

<div style='border:solid 1px #CCC; padding-left:15px; padding-top:15px; padding-bottom:5px'> 

<table>   
<tr><td>
    <b>{gt text="Shop Name"}</b>:</td><td> 
    {if $perm eq 1}
    <a href='{modurl  modname='ZSELEX' type='user' func='shop' shop_id=$smarty.request.shop_id}'>{$item.shop_name|upper} </a>
    {else}
    {$miniShopLinkStrt}{$item.shop_name|upper}{$miniShopLinkEnd}   
    {/if}    
</td></tr>
<tr><td>
<b>{gt text="Address"}</b>:</td><td> {$item.address}</td></tr>
<tr><td>
<b>{gt text="Telephone"}</b>:</td><td> {$item.telephone}</td></tr>
<tr><td>
<b>{gt text="Fax"}</b>:</td><td> {$item.fax}</td></tr>
<tr><td>
<b>{gt text="Email"}</b>:</td><td> {$item.email}</td></tr>

<tr><td><b>{gt text="Diskquota"}</b>:</td><td> {$ownerfoldersize} {gt text="used of"} {$ownerfolderquota}</td></tr>
</table>
</div>

 {adminfooter}
