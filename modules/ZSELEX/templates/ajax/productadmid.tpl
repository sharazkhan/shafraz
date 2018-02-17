{counter assign=idx start=0 print=0}
{foreach item='product' from=$midad}
{counter}
{if $product.SHOPTYPE eq 'iSHOP'}
{setdiscount value=$product.discount orig_price=$product.original_price product_id=$product.product_id}
<a href="{modurl modname="ZSELEX" type="user" func="productview" id=$product.products_id}">
   <div class="col-sm-3 col-xs-6 special-sub-product hover-border nopadding">
    {if $is_discount}
       <span class="offer-pop">-{$dicount_value}</span>
        {/if}
        <div class="thumbnail">
            <div class="pro-image">
                {assign var="imagepath" value="zselexdata/`$product.SHOPID`/products/thumb/`$product.products_image`"}
                {if file_exists($imagepath) && $product.products_image neq ''} 
                {imageproportional image=$product.products_image path="`$baseurl`zselexdata/`$product.SHOPID`/products/thumb" height="90" width="128"}
                <img {$imagedimensions} class="lazy"  src="{$baseurl}zselexdata/{$product.SHOPID}/products/thumb/{$product.products_image|replace:' ':'%20'}" alt="{$product.products_name}">

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

    </div>
</a>
{else}
     <a href="http://{$product.domainname}/index.php?main_page=product_info&products_id={$product.products_id}">
    <div class="col-sm-3 col-xs-6 special-sub-product hover-border nopadding">
        <div class="thumbnail">
            <div class="pro-image">
                {if $product.file_exists1}
                  <img class="lazy" {if !empty($product.W)} style="width:{$product.W}px;height:{$product.H}px" {else} width="170px" {/if} src="{$baseurl}modules/ZSELEX/images/grey_small.gif" data-original="http://{$product.domainname}/images/{$product.products_image}" >
               {/if}
            </div>
            <div class="product-caption">
                <h3>
                   {if $product.manufacturers_name neq ''}{shorttext len=40 text=$product.manufacturers_name|cleantext}{/if}
                </h3>
                <div class="pro-sub-row clearfix">
                    <div class="product-amount">
                      {displayprice amount=$product.products_price}
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>
{/if}
{/foreach}

