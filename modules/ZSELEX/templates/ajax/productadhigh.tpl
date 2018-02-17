<div class="row">
    {foreach item='product' from=$highad}
    {if $product.SHOPTYPE eq 'iSHOP'}
    {setdiscount value=$product.discount orig_price=$product.original_price product_id=$product.product_id}
    <a href="{modurl modname="ZSELEX" type="user" func="productview" id=$product.products_id}">
       <div class="col-sm-6 special-product hover-border">
            <div class="thumbnail">
                {if $is_discount}
                    <span class="offer-pop">-{$dicount_value}</span>
                {/if}
                {assign var="imagepath" value="zselexdata/`$product.SHOPID`/products/thumb/`$product.products_image`"}
                <div class="pro-image">
                    {if file_exists($imagepath) && $product.products_image neq ''} 
                    {imageproportional image=$product.products_image path="`$baseurl`zselexdata/`$product.SHOPID`/products/thumb" height="145" width="170"}    
                    <img {$imagedimensions} class="lazy"  src="{$baseurl}zselexdata/{$product.SHOPID}/products/thumb/{$product.products_image|replace:' ':'%20'}" alt="{$product.products_name}">
                    {/if}
                </div>
                <div class="product-caption">
                    <h3>
                        {if $product.products_name neq ''}
                        {shorttext text=$product.products_name|cleantext len=35} 
                        {/if}
                    </h3>
                    <div class="pro-sub-row clearfix">
                        <div class="product-sub-text">
                            <span>{gt text='Shop'}:</span>
                            <span class="sub-name">{$product.shopName|cleantext}</span>
                        </div>
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
        <div class="col-sm-6 special-product hover-border">

            <div class="thumbnail">
                {assign var="imagepath" value="zselexdata/`$product.SHOPID`/products/thumb/`$product.products_image`"}
                <div class="pro-image">
                    {if $product.file_exists1}
                    <img class="lazy" {if !empty($product.W)} style="width:{$product.W}px;height:{$product.H}px" {else} width="170px" {/if} src="{$baseurl}modules/ZSELEX/images/grey_small.gif" data-original="http://{$product.domainname}/images/{$product.products_image}" >
                         {/if}
                </div>
                <div class="product-caption">
                    <h3>
                        {if $product.manufacturers_name neq ''}{$product.manufacturers_name|cleantext}{/if}
                    </h3>
                    <div class="pro-sub-row clearfix">
                        <div class="product-sub-text">
                            <span>{gt text='Shop'}:</span>
                            <span class="sub-name">{$product.shopName|cleantext}</span>
                        </div>
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

</div>