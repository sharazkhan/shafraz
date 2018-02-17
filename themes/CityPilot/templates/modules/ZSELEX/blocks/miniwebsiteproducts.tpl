<script>
jQuery(document).ready(function() {
    jQuery("img.lazy").lazyload();
});
</script>
{fileversion}
{pageaddvar name='javascript' value="modules/ZSELEX/javascript/product_user.js$ver"} 
{if $perm}
<div class="OrageEditSec EditProducts"><a href="{modurl modname="ZSELEX" type="admin" func="products" shop_id=$smarty.request.shop_id}"><img src="{$themepath}/images/OrageEdit.png">{gt text='Edit Products'}</a></div>
 {/if}
<div id="minisiteproduct_block" class="ImageSection" style="width:100%;">
    <h4>{gt text='Products'}</h4>
    {if $linkToShop}
    <div class="SeALL">
        <div class="SeAllText"><a href='{modurl modname="ZSELEX" type="user" func="shop" shop_id=$shop_id}'>{gt text='See All'} &nbsp;&nbsp;<img src="{$themepath}/images/RightArow.png"></a> </div>
    </div>
    {/if}
    {if $productCount > 0}
    <div style="display: block; width: 100%; height: auto">
    <ul class="Product-Images-Sequence">
        {if $shoptype eq 'iSHOP'}
         {foreach item='item' from=$products}
             {setdiscount value=$item.discount orig_price=$item.original_price product_id=$item.product_id}
            
            
           <li> 
               <a href='{modurl  modname='ZSELEX' type='user' func='productview' id=$item.product_id}'>
            <div class="FlexiImg">
                {*<img src="{$themepath}/images/P1.png" />*}
                  {assign var="imagepath" value="zselexdata/`$shop_id`/products/thumb/`$item.prd_image`"}
                   
                  {if file_exists($imagepath) && $item.prd_image neq ''} 
                    {imageproportional image=$item.prd_image path="`$baseurl`zselexdata/`$shop_id`/products/thumb" height="190" width="190"}
                    <img class="lazy" src="{$baseurl}modules/ZSELEX/images/grey_small.gif" data-original="{$baseurl}zselexdata/{$shop_id}/products/thumb/{$item.prd_image}"  {$imagedimensions} />
                  {else}
                    <img class="lazy" src="{$baseurl}modules/ZSELEX/images/grey_small.gif" data-original="zselexdata/nopreview.jpg"  width="150" height="150"/>
                  {/if}
                   
            </div>
                  {* {$baseurl}modules/ZSELEX/images/grey_small.gif *}
                   
                   {if $is_discount}
            <div class="Circle"  style="display:block"><p class="CText">-{$dicount_value}</p></div>
                  {/if}
            <p class="PText tooltips" title="{$item.product_name|cleantext}"  {*if $is_discount} style="margin:8px"  {/if*}>
                {shorttext text=$item.product_name len=22}
                 {if $item.prd_price > 0}
                    <br />

                    {if $is_discount}
                    <span style="text-decoration:line-through; color:red; font-size:14px"><span style="color:#717D82">{displayprice amount=$item.original_price} DKK</span></span><br>
                    {else}
                    <span style="font-size:14px"><span style="color:#717D82">&nbsp;</span></span><br>
                    {/if}
                    {if $is_discount}
                     {displayprice amount=$dicount_price} DKK
                    {else}
                     {displayprice amount=$item.prd_price} DKK
                    {/if}
                {/if}
            </p>
            </a>
             {if !$no_payment}
            {*<div class="Box BoxId{$item.product_id}" id="BoxId{$item.product_id}">
                
                 {product_option_exist product_id=$item.product_id}
                 {if !$optionExist}
                <a id="buytxt{$item.product_id}" href="" onClick="addToCart('{$item.product_id}','{$smarty.request.shop_id}','{$loggedIn}');return false;"  class="addbutton">{gt text='Buy'}</a> 
                 {else}
                <a id="buytxt{$item.product_id}" href="{modurl modname="ZSELEX" type="user" func="productview" id=$item.product_id}"  class="addbutton">{gt text='Buy'}</a> 
                 {/if}
                <span id="addloader{$item.product_id}"></span>
            </div>*}
             {product_option_exist product_id=$item.product_id}
                   {if !$optionExist} 
                        {if $item.prd_quantity > 0}
                             {if $item.prd_price > 0}
                    <div class="Box BoxId{$item.product_id}" id="BoxId{$item.product_id}">
                        {* <a href="javascript:document.cart_quantity{$item.product_id}.submit()"  class="addbutton">{gt text='Buy'}</a>*}
                        <a id="buytxt{$item.product_id}" href="" onClick="addToCartOptions('{$item.product_id}','{$smarty.request.shop_id}','{$loggedIn}');return false;"  class="addbutton">{gt text='Buy'}</a> 
                        <span id="addloader{$item.product_id}"></span>
                    </div>
                             {/if}
                         {else}
                              <div class="Box BoxId{$item.product_id}" style="cursor:default;background:none;color:grey;width:auto" id="BoxId{$item.product_id}">
                             {gt text='Out Of Stock!'}
                             </div>
                         {/if}
                   {else}
                           {if $optionQty > 0}
                                {if $item.prd_price > 0}
                            <div class="Box BoxId{$item.product_id}" id="BoxId{$item.product_id}">
                               <a id="buytxt{$item.product_id}" href="{modurl modname="ZSELEX" type="user" func="productview" id=$item.product_id}"   class="addbutton">{gt text='Buy'}</a> 
                               <span id="addloader{$item.product_id}"></span>
                           </div>
                           {/if}
                            {else}
                            <div class="Box BoxId{$item.product_id}" style="cursor:default;background:none;color:grey;width:auto" id="BoxId{$item.product_id}">
                             {gt text='Out Of Stock!'}
                             </div>
                           {/if}
                  {/if}
            {/if}
           {*  <span id="addloader{$item.product_id}"></span>  *}
             <form name="cart_quantity{$item.product_id}" action="{modurl  modname='ZSELEX' type='user' func='cart'}" {*action="{$baseurl}index.php?module=zselex&type=user&func=cart"*} method="post" enctype="multipart/form-data">
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
        {foreach item='item' from=$products}  
        <li> 
             <a href='http://{$zShopDomain}/index.php?main_page=product_info&products_id={$item.products_id}' target='_blank'>
            <div class="FlexiImg">
                {*<img src="{$themepath}/images/P1.png" />*}
                  {imageproportional image=$item.products_image path=$zShopImgPath height="210" width="170"}
                
                <img class="lazy" src="{$baseurl}modules/ZSELEX/images/grey_small.gif" data-original="{$zShopImgPath}/{$item.products_image}"  {$imagedimensions} />
                
            </div>
            <div class="Circle"  style="display:none"><p class="CText">50%</p></div>
            <p class="PText">
                 <!--{$shopName}&nbsp;-->{$item.products_name|cleantext}<br />{displayprice amount=$item.products_price} DKK
            </p>
            </a>
            <div class="Box"><a href="javascript:document.cart_quantity{$item.products_id}.submit()"  class="addbutton">{gt text='Buy'}</a></div>
               <form name="cart_quantity{$item.products_id}" action="http://{$zShopDomain}/index.php?main_page=product_info&action=add_product&products_id={$item.products_id}" method="post" enctype="multipart/form-data" target="_blank">
                        <input type="hidden" name="cart_quantity" value="1" />
                        <input type="hidden" name="products_id" value="{$item.products_id}" />
              </form>
        </li>
         {/foreach}
         {/if}   
     </ul>
     </div>
	{/if}
  </div> 
  
  
     <script type="text/javascript">
                        Zikula.UI.Tooltips($$('.tooltips'));
     </script>
                       
 <script>
    var defwindowajax = new Zikula.UI.Window($("miniSiteImageInfo"),{resizable: true});
    jQuery("#minisiteproduct_block").prevAll('h4:first').css("display", "none");
 </script>