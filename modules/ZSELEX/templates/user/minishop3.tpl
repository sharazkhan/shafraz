{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/product_user.js'} 
{pageaddvar name="stylesheet" value="themes/CityPilot/style/minishopproducts.css"}

{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/lazyload/lazyload.js'} *}
{dropdownlist}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/tpl/minishop.js'}
{*
<script type="text/javascript">
    var defwindowajax = new Zikula.UI.Window($('miniShopProducts'),{resizable: true});
      jQuery(function () {
        var selcat = Zikula.__('select category', 'module_zselex_js');
        var selmanf = Zikula.__('select manufacturer', 'module_zselex_js');
        jQuery(".mcategory").dropdownchecklist( { emptyText:selcat ,  maxDropHeight: 150, width: 150 } );
        jQuery(".mmanuf").dropdownchecklist( { emptyText: selmanf ,  maxDropHeight: 150, width: 150 } );
      }); 
</script>
*}
{if $total_count > 0}
  <div align="right" style="padding-left:710px">
     {pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages='5'} 
  </div>
 {/if}
{* Purpose of this template: Display products within an external context *}
{if $shoptype eq 'iSHOP'}

 {if $perm}
<div class="OrageEditSec mainHeadEdit">
    <a href="{modurl modname="ZSELEX" type="admin" func="products" shop_id=$smarty.request.shop_id}">
        <img src="themes/CityPilot/images/OrageEdit.png">{gt text='Edit Products'}</a>
</div>
  {/if}
  {*
  <script>
     jQuery(document).ready(function() {
    jQuery("img.lazy").lazyload();
});
        </script>
        *}
  {if $shoptype eq 'iSHOP'}
   <div> <div style="display:inline-block;padding:0px 10px; float:right">
  <form  class="z-form" id="cat_filter" id="cat_filter"  action="{modurl modname="ZSELEX" type="user" func="shop" shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
 
   <input type="hidden" name="startnum" value="{$startnum}" />
   {*<input type="hidden" name="prod_mnfr" value="{$mnfr_id}" />*}
   <input type="hidden" name="submit_category" value="1" />
   <input type="hidden" name="mnfrIds" value="{$manfIds}" />
   {*<input type="hidden" name="prod_categorys" value="{$prod_catIds}" />*}
  <span  class="right">
      <select  class='mcategory' multiple='multiple' name='prod_category[]'  id="prod_category" onchange="document.forms['cat_filter'].submit();">
        
            {foreach from=$categories  item='item'}
                <option value="{$item.prd_cat_id}"  {foreach from=$prod_catIdsArr item=itm} {if $item.prd_cat_id eq $itm} selected="selected" {/if} {/foreach} > {$item.prd_cat_name} </option>
            {/foreach}
      </select>
  </span>
     
</form>
      </div>
      <div style="display:inline-block;padding:0px 10px; float:right">
   <form  class="z-form" id="mnfr_filter"  action="{modurl modname="ZSELEX" type="user" func="shop" shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
 
   <input type="hidden" name="startnum" value="{$startnum}" />
   {*<input type="hidden" name="prod_category" value="{$prod_catId}" />*}
   <input type="hidden" name="prod_categorys" value="{$prod_catIds}" />
   <input type="hidden" name="submit_mnfr" value="1" />
  <span class="right">
      <select multiple='multiple' class='mmanuf' name='prod_mnfr[]'  id="prod_mnfr" onchange="document.forms['mnfr_filter'].submit();">
        
            {foreach from=$manufacturers  item='manufacturer'}
                <option value="{$manufacturer.manufacturer_id}" {foreach from=$manfIdsArr item=itm1} {if $manufacturer.manufacturer_id eq $itm1} selected="selected" {/if} {/foreach}   > {$manufacturer.manufacturer_name} </option>
            {/foreach}
      </select>
  </span>
     
    </form>
      </div>
   </div>
      {/if}      
{if $count > 0}
<div class="ImageSection">
   
    <ul class="Product-Images-Sequence">
        {foreach item='item' from=$products}
            {setdiscount value=$item.discount orig_price=$item.original_price product_id=$item.product_id}
        <li> 
            <div class="FlexiImg">
                <a href='{modurl  modname='ZSELEX' type='user' func='productview' id=$item.product_id}'>
                     {assign var="imagepath" value="zselexdata/`$shop_id`/products/thumb/`$item.prd_image`"}
                     {if file_exists($imagepath) && $item.prd_image neq ''} 
                     {imageproportional image=$item.prd_image path="`$baseurl`zselexdata/`$shop_id`/products/thumb" height="190" width="190"}
                    <img class="lazy" src="{$baseurl}modules/ZSELEX/images/grey_small.gif" data-original="{$baseurl}zselexdata/{$shop_id}/products/thumb/{$item.prd_image}" {$imagedimensions} {*{if $item.H  neq  ''}height='{$item.H}'  width='{$item.W}'{/if}*} />
                    {else}
                    <img class="lazy"  src="{$baseurl}modules/ZSELEX/images/grey_small.gif"  data-original="zselexdata/nopreview.jpg"  width="150" height="150"/>
                    {/if}   
                </a>
            </div>
            {if $is_discount}
            <div class="Circle"  style="display:block"><p class="CText">-{$dicount_value}</p></div>
                  {/if}
            <p class="PText tooltips" title="{$item.product_name}" {*if $is_discount} style="margin:8px"  {/if*}>
                {shorttext text=$item.product_name len=22}
                {if $item.prd_price > 0}
                <br />
                 {if $is_discount}
                <span style="text-decoration:line-through; color:red; font-size:14px"><span style="color:#717D82">{displayprice amount=$item.original_price} DKK</span></span><br>
                {else}
                <span style="font-size:14px"><span style="color:#717D82">&nbsp;</span></span><br>
                {/if}
                {displayprice amount=$item.prd_price} DKK
                {/if}
            </p>
           {if !$no_payment}
                {product_option_exist product_id=$item.product_id}
                   {if !$optionExist OR $item.prd_question!=''} 
                     {if $item.prd_quantity > 0}
                      {if $item.prd_price > 0}
                    <div class="Box BoxId{$item.product_id}" id="BoxId{$item.product_id}">
                        {* <a href="javascript:document.cart_quantity{$item.product_id}.submit()"  class="addbutton">{gt text='Buy'}</a>*}
                        {if $item.enable_question < 1}
                        <a id="buytxt{$item.product_id}" href="" onClick="addToCartOptions('{$item.product_id}','{$smarty.request.shop_id}','{$loggedIn}');return false;"  class="addbutton">{gt text='Buy'}</a> 
                        {else}
                        <a id="buytxt{$item.product_id}" href="{modurl modname="ZSELEX" type="user" func="productview" id=$item.product_id}"   class="addbutton">{gt text='Buy'}</a> 
                        {/if}
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
              {*<span id="addloader{$item.product_id}"></span>*}
           
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
    </ul>
</div>
{else}
<div class="ImageSection" style="width:100%;">
    <ul class="Product-Images-Sequence">
      <span style="padding-left:20px" align="center">{gt text='No products found.'}</span>
    </ul>
</div>
{/if}
{elseif $shoptype eq 'zSHOP'}
{* Purpose of this template: Display products within an external context *}
 <script>
     jQuery(document).ready(function() {
    jQuery("img.lazy").lazyload();
});
 </script>
<div class="ImageSection">
    <ul class="Product-Images-Sequence">
        {foreach item='item' from=$products}
        <li> 
            <div class="FlexiImg">
                {if $item.products_image neq ''} 
                 {imageproportional image=$item.products_image path=$zShopImgPath height="210" width="170"}
                <a href='http://{$zShopDomain}/index.php?main_page=product_info&products_id={$item.products_id}'>
                    <img class="lazy" src="{$baseurl}modules/ZSELEX/images/grey_small.gif" data-original="{$zShopImgPath}/{$item.products_image}"  {$imagedimensions}/>
                </a>
                  {else}
                    
                  <img class="lazy"  src="{$baseurl}modules/ZSELEX/images/grey_small.gif"  data-original="{$baseurl}zselexdata/nopreview.jpg"  width="150" height="150"/>
                  {/if}  
            </div>
            <div class="Circle"  style="display:none"><p class="CText">50%</p></div>
            <p class="PText">{$item.manufacturers_name}<br />{displayprice amount=$item.products_price} DKK
            </p>
           <div class="Box"><a href="javascript:document.cart_quantity{$item.products_id}.submit()"  class="addbutton">{gt text='Buy'}</a></div>
             <form name="cart_quantity{$item.products_id}" action="http://{$zShopDomain}/index.php?main_page=product_info&action=add_product&products_id={$item.products_id}" method="post" enctype="multipart/form-data" target="_blank">
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