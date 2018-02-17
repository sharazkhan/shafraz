 {if $perm}
<div class="OrageEditSec EditProducts"><a href="{modurl modname="ZSELEX" type="admin" func="products" shop_id=$smarty.request.shop_id}"><img src="themes/CityPilot/images/OrageEdit.png">{gt text='Edit Products'}</a></div>
 {/if}
<h3>{gt text='Products'}</h3>
 {if $linkToShop}
<div class="SeALL">
    <div class="SeAllText"><a href='{modurl modname="ZSELEX" type="user" func="minishop" shop_id=$shop_id}'>{gt text='See all'} &nbsp;&nbsp;<img src="{$themepath}/images/RightArow.png"></a> </div>
</div>
 {/if}

<ul class="Product-Images-Sequence">
    {if $shoptype eq 'iSHOP'}
         {foreach item='item' from=$products key=k}
             {setdiscount value=$item.discount orig_price=$item.original_price}
    <li  {if $k+1 eq $productCount} class="lastImg"  {/if}> 
        <div class="FlexiImg">
           {assign var="imagepath" value="zselexdata/`$ownerName`/products/thumb/`$item.prd_image`"}
                   <a href='{modurl  modname='ZSELEX' type='user' func='productview' id=$item.product_id}'>
                  {if file_exists($imagepath) && $item.prd_image neq ''} 
                       {imageproportional image=$item.prd_image path="`$baseurl`zselexdata/`$ownerName`/products/thumb" height="190" width="190"}
                    <img src="{$baseurl}zselexdata/{$ownerName}/products/thumb/{$item.prd_image}"  {$imagedimensions} />
                  {else}
                    <img src="zselexdata/nopreview.jpg"  width="150" height="150"/>
                  {/if}
                   </a>
        </div>
       {if $is_discount}
        <div class="Circle"> <p class="CText">{$dicount_value}</p></div>
        {/if}
        {if $is_discount}
        <span style="text-decoration:line-through; color:red; font-size:14px"><span style="color:#717D82">{displayprice amount=$item.original_price} DKK</span></span>
        {else}
             <span style="font-size:14px"><span style="color:#717D82">&nbsp;</span></span>
        {/if}
        <p class="PText tooltips" title="{$item.product_name}"><span>{shorttext text=$item.product_name len=11}</span><span class="right">{displayprice amount=$item.prd_price} DKK</span>
        </p>
        <div class="Box"><a href="javascript:document.cart_quantity{$item.product_id}.submit()"  class="addbutton">{gt text='add to cart'}</a></div>
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
    
    {elseif $shoptype eq 'zSHOP'}
    
     {foreach item='item' from=$products key=k}
            
    <li  {if $k+1 eq $productCount} class="lastImg"  {/if}> 
        <div class="FlexiImg">
                   <a href='http://{$item.domain}/index.php?main_page=product_info&products_id={$item.products_id}' target='_blank'>
                     {imageproportional image=$item.products_image path="http://`$item.domain`/images" height="190" width="190"}
                    <img src="http://{$item.domain}/images/{$item.products_image}"  {$imagedimensions} />
                    </a>
        </div>

        <div class="Circle"> <p class="CText">50%</p></div>
        <p class="PText"><span>{$item.products_name|wordwrap:20:"\n":true}</span><span class="right">{$item.PRICE} DKK</span>
        </p>
        <div class="Box"><a href="javascript:document.cart_quantity{$item.products_id}.submit()"  class="addbutton">{gt text='add to cart'}</a></div>
          <form name="cart_quantity{$item.products_id}" action="http://{$item.domain}/index.php?main_page=product_info&action=add_product&products_id={$item.products_id}" method="post" enctype="multipart/form-data" target="_blank">
        <input type="hidden" name="cart_quantity" value="1" />
         <input type="hidden" name="products_id" value="{$item.products_id}" />
         </form>

    </li>
    {/foreach}
   
    
    {/if}
    
   
   {* <li class="lastImg"> 
        <div class="FlexiImg"><img src="{$themepath}/images/SkinImgSec4.png" /></div>
        <div class="Circle"> <p class="CText">50%</p></div>

        <p class="PText"><span>Ecco obbia</span><span class="right">800 DKK</span>
        </p>
        <div class="Box">læg i kurv</div>
    </li>*}

</ul>
    
     <script type="text/javascript">
                        Zikula.UI.Tooltips($$('.tooltips'));
     </script>