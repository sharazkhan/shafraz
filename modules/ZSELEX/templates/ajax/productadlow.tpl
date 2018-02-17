 
{counter assign=idx start=0 print=0}
{foreach item='product' from=$lowad}
{counter}
{setdiscount value=$product.discount orig_price=$product.original_price product_id=$product.product_id}
<a href="{modurl modname="ZSELEX" type="user" func="productview" id=$product.products_id}">
   <div class="col-sm-3 col-xs-6 special-sub-product hover-border nopadding">
          <!--<a href="{modurl modname="ZSELEX" type="user" func="productview" id=$product.products_id}">-->
             {if $is_discount}
       <span class="offer-pop">-{$dicount_value}</span>
        {/if}
            <div class="thumbnail">
                <div class="pro-image">
                    {assign var="imagepath" value="zselexdata/`$product.SHOPID`/products/thumb/`$product.products_image`"}
                    {if file_exists($imagepath) && $product.products_image neq ''} 
                    {imageproportional image=$product.products_image path="`$baseurl`zselexdata/`$product.SHOPID`/products/thumb" height="90" width="128"}
                    <img {$imagedimensions} class="lazy"   src="{$baseurl}zselexdata/{$product.SHOPID}/products/thumb/{$product.products_image|replace:' ':'%20'}" alt="{$product.products_name}">

                    {/if}
                </div>
                <div class="product-caption">
                    <h3>
                        {if $product.products_name neq ''}{shorttext text=$product.products_name|cleantext len=25}{/if}
                    </h3>
                    <div class="pro-sub-row clearfix">
                        <div class="product-amount">
                            {if $is_discount}
                            {displayprice amount=$dicount_price} DKK
                            {else}
                            {displayprice amount=$product.prd_price} DKK 
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        <!--</a>-->
    </div>
</a>
{/foreach}
