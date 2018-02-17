
{pageaddvar name="stylesheet" value="themes/CityPilot/style/minishopproducts.css"}
<script type="text/javascript">
    var defwindowajax = new Zikula.UI.Window($('miniShopProducts'),{resizable: true });
</script>
{if $total_count > 0}
<div align="right" style="padding-left:710px">
    {pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages='5' }
</div>
{/if}
{* Purpose of this template: Display products within an external context *}
{if $shoptype eq 'iSHOP'}
<div>
    <ul class="Product-Images-Sequence FullProduct">
        {foreach item='item' from=$products}
                {setdiscount value=$item.discount orig_price=$item.original_price}
        <li> 
            <div class="FlexiImg">
                <a href='{modurl  modname='ZSELEX' type='user' func='productview' id=$item.product_id}'>
                   {assign var="imagepath" value="zselexdata/`$ownerName`/products/thumb/`$item.prd_image`"}
                   {if file_exists($imagepath) && $item.prd_image neq ''} 
                   <img src="{$baseurl}zselexdata/{$ownerName}/products/thumb/{$item.prd_image}" {if $item.H  neq  ''}height='{$item.H}'  width='{$item.W}'{/if} />
                     {else}
                     <img src="zselexdata/nopreview.jpg"  width="150" height="150"/>
                    {/if}   
                </a>
            </div>
                 {if $is_discount}
             <div class="Circle"> <p class="CText">{$dicount_value}</p></div>
               {/if}
             <p class="PText tooltips" title="{$item.product_name}"><span>{shorttext text=$item.product_name len=11}</span><span class="right">{displayprice amount=$item.prd_price} DKK</span>
            </p>
            <div class="Box"><a href="javascript:document.cart_quantity{$item.product_id}.submit()"  class="addbutton">{gt text='Buy'}</a></div>
            <form name="cart_quantity{$item.product_id}" action="{modurl  modname='ZSELEX' type='user' func='cart'}" {*action="{$baseurl}index.php?module=zselex&type=user&func=cart"*} method="post" enctype="multipart/form-data" target="_blank">
                <input type="hidden" name="cart_quantity" value="1" />
                <input type="hidden" name="product_id" value="{$item.product_id}" /> 
                <input type="hidden" name="productName" value="{$item.product_name}">
                <input type='hidden' name='product_price' value="{$item.prd_price}" >   
                <input type='hidden' name='productImage' value="{$item.prd_image}" >
                <input type='hidden' name='productDesc' value="{$item.prd_description}" >
                <input type='hidden' name='shop_id' value="{$item.shop_id}" >
            </form>
        </li>
        {/foreach}
    </ul>
</div>
{elseif $shoptype eq 'zSHOP'}
{* Purpose of this template: Display products within an external context *}
<div>
    <ul class="Product-Images-Sequence">
        {foreach item='item' from=$products}
        <li> 
            <div class="FlexiImg">
                <a href='http://{$item.domain}/index.php?main_page=product_info&products_id={$item.products_id}'>
                    <img  src="http://{$item.domain}/images/{$item.products_image}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px' {/if}/>
                </a>
            </div>
            <div class="Circle"  style="display:none"><p class="CText">50%</p></div>
            <p class="PText">{$item.manufacturers_name}<br />{$item.PRICE} DKK
            </p>
            <div class="Box"><a href="javascript:document.cart_quantity{$item.products_id}.submit()"  class="addbutton">{gt text='Buy'}</a></div>
            <form name="cart_quantity{$item.products_id}" action="http://{$item.domain}/index.php?main_page=product_info&action=add_product&products_id={$item.products_id}" method="post" enctype="multipart/form-data" target="_blank">
                <input type="hidden" name="cart_quantity" value="1" />
                <input type="hidden" name="products_id" value="{$item.products_id}" />
            </form>
        </li>
        {/foreach}
    </ul>
</div>
{else}
<dt> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gt text='No products found.'}</dt> 
{/if}  
{if $total_count > 0}
<div align="right" style="padding-left:710px">
    {pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages='5'}
</div>
{/if}

<script type="text/javascript">
                        Zikula.UI.Tooltips($$('.tooltips'));
     </script>

