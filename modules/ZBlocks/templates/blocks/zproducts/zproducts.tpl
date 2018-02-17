
{pageaddvar name="stylesheet" value="modules/ZBlocks/style/minishopproducts.css"}

{if $count > 0}
<div class="ImageSection">
   
    <ul class="Product-Images-Sequence">
        {foreach item='item' from=$products}
            {setdiscount value=$item.discount orig_price=$item.original_price}
        <li> 
            <div class="FlexiImg">
                <a href="{$domain}/zselex/productview/{$shop_title}/{$item.urltitle}">
                     {assign var="imagepath" value="`$domain`/zselexdata/`$shop_id`/products/thumb/`$item.prd_image`"}
                     {fileexist path=$imagepath}
                     {if $fileexist && $item.prd_image neq ''} 
                     {imageproportional image=$item.prd_image path="`$domain`/zselexdata/`$shop_id`/products/thumb" height="190" width="190"}
                    <img src="{$domain}/zselexdata/{$shop_id}/products/thumb/{$item.prd_image}" {$imagedimensions} {*{if $item.H  neq  ''}height='{$item.H}'  width='{$item.W}'{/if}*} />
                    {else}
                    <img  src="zselexdata/nopreview.jpg"  width="150" height="150"/>
                    {/if}   
                </a>
            </div>
            {if $is_discount}
            <div class="Circle"  style="display:block"><p class="CText">{$dicount_value}</p></div>
                  {/if}
            <p class="PText tooltips" title="{$item.product_name}" {*if $is_discount} style="margin:8px"  {/if*}>
                {shorttext text=$item.product_name len=22}<br />
                 {if $is_discount}
                <span style="text-decoration:line-through; color:red; font-size:14px"><span style="color:#717D82">{displayprice amount=$item.original_price} DKK</span></span><br>
                {else}
                <span style="font-size:14px"><span style="color:#717D82">&nbsp;</span></span><br>
                {/if}
                {displayprice amount=$item.prd_price} DKK
            </p>
                         
                            <div class="Box BoxId{$item.product_id}" id="BoxId{$item.product_id}">
                               <a id="buytxt{$item.product_id}" href="{$domain}/zselex/productview/{$shop_title}/{$item.urltitle}"   class="addbutton">{gt text='Buy'}</a> 
                               <span id="addloader{$item.product_id}"></span>
                           </div>
                            
              {*<span id="addloader{$item.product_id}"></span>*}
           
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

