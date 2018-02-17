
{adminheader}
{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/test1.js'}
{include file="admin/orderlinks.tpl"}
<div class="z-admin-content-pagetitle">
    <h3>{gt text='Services Cart'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="updateServiceCart"}" method="post" enctype="multipart/form-data">
      <div class="z-formrow">
        <label for="plugin"></label>
        <table width="30%" class="z-datatable">
            <thead>
                <tr>
                    <td><b>{gt text='Bundle'}</b></td>
                    <td><b>{gt text='Shop'}</b></td>
                    <td><b>{gt text='Quantity'}</b></td>
                    <td><b>{gt text='Original Price'}</b></td>
                    <td><b>{gt text='Price'}</b></td>
                    <td><b>{gt text='sub Total'}</b></td>
                    <td><b>{gt text='Delete'}</b></td>
                </tr>
            </thead>
            <tbody>
                {foreach  item='item' from=$serviceCart}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>
                        {* {$item.type} *} 
                        {$item.bundle_name}
                    </td>
                    <td>{$item.shop_name}</td>
                    <td><input type="text" {if $item.qty_based eq 0} readonly {/if} name="quantity[{$item.basket_id}+{$item.finalprice}]" value="{$item.quantity}"></td>
                    <td>{displayprice amount=$item.original_price}</td>
                    <td>{displayprice amount=$item.price}</td>
                    <!--<td>{if $item.subtotal < 1}<b><font color='red'>Free Trial</font></b>{else}{$item.subtotal}{/if}</td>-->
                    <td>{displayprice amount=$item.subtotal}</td>
                    <td>
                        <a href='{modurl modname='ZSELEX' type='admin' func='deleteServiceCart' basket_id=$item.basket_id}'>{gt text='Delete'}</a>
                    </td>
                </tr>
                {foreachelse}
                <tr>
                    <td align="center" colspan="7">{gt text='Service Cart is empty'}</td>
                </tr> 
                {/foreach}
                <tr>
                    <td align="right" colspan="7" style="padding-right:60px"><b>{gt text='Grand Total:'} {displayprice amount=$granTotal}</b></td>
                </tr>
            </tbody>

        </table>
         {if $count > 0}       
        <div align="right"><input type="submit" name="updatecart" value="{gt text='Update Cart'}"></div>
        <div align="right">
           {* <input type="button" name="Proceed" value="{gt text='Place Order'}" onclick="window.location.href='{modurl modname="ZSELEX" type="admin" func="serviceOrder"}'">*}
            <input type="button" name="Proceed" value="{gt text='Go to payment'}" onclick="window.location.href='{modurl modname="ZSELEX" type="admin" func="paymentOption"}'">
        </div>
         {/if}
    </div>
</form>

{adminfooter}