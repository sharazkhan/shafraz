{pageaddvar name='javascript' value="$themepath/javascript/product_user.js?v=1.1"} 


{if $perm}
<a href="{modurl modname="ZSELEX" type="admin" func="products" shop_id=$smarty.request.shop_id}" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>{gt text='Edit Products'}</a>
{/if}

{if $productCount > 0}
<div class="products-wrap clearfix col-sm-12 product-list">
    <div class="product-head clearfix">
        <h3 class="pull-left">Products</h3>
        {if $linkToShop}
        <a href="{modurl modname="ZSELEX" type="user" func="shop" shop_id=$shop_id}" class="see-all pull-right">{gt text='See All'}  <i class="fa fa-caret-right"></i></a>
        {/if}
    </div>
    <div class="row">
        {foreach item='item' from=$products}
        {setdiscount value=$item.discount orig_price=$item.original_price product_id=$item.product_id}
        <div class="col-sm-4 col-xs-6 btm-product-list hover-border">
            <div class="thumbnail text-center">
                <a href="{modurl  modname='ZSELEX' type='user' func='productview' id=$item.product_id}">
                    {assign var="imagepath" value="zselexdata/`$shop_id`/products/thumb/`$item.prd_image`"}
                    <div class="pro-image">
                        {if file_exists($imagepath) && $item.prd_image neq ''} 
                        {imageproportional image=$item.prd_image path="`$baseurl`zselexdata/`$shop_id`/products/medium" height="150" width="244"}
                        <img src="{$baseurl}zselexdata/{$shop_id}/products/medium/{$item.prd_image}" {$imageProperty} class="img-responsive" alt="">
                        {else}
                        <img class="img-responsive"  src="{$themepath}/images/no-image.jpg"  width="150" height="150"/>
                        {/if}
                    </div>
                    <div class="btm-product-name clearfix">
                        <h4>{shorttext text=$item.product_name len=22}</h4>
                        {if $item.prd_price > 0}
                        {if $is_discount}
                        <span style="text-decoration:line-through; color:red; font-size:14px"><span style="color:#717D82">{displayprice amount=$item.original_price} DKK</span></span><br>
                        {else}
                        <span style="font-size:14px"><span style="color:#717D82">&nbsp;</span></span><br>
                        {/if}
                        <h5>
                            {if $is_discount}
                            {displayprice amount=$dicount_price} DKK
                            {else}
                            {displayprice amount=$item.prd_price} DKK
                            {/if}
                        </h5>
                        {/if}
                    </div>
                </a>
                {if !$no_payment}
                {product_option_exist product_id=$item.product_id}
                {if !$optionExist} 
                <div class="Box BoxId{$item.product_id}" id="BoxId{$item.product_id}">
                    {if $item.prd_quantity > 0}
                    {if $item.prd_price > 0}
                    <a id="buytxt{$item.product_id}" href="#" class="btn buy-btn" onClick="addToCartOptions('{$item.product_id}', '{$smarty.request.shop_id}', '{$loggedIn}');return false;"> {gt text="BUY"}</a>
                   {* <span id="addloader{$item.product_id}"></span>*}
                    {/if}
                    {else}
                    {gt text='Out Of Stock!'}    
                    {/if}
                </div>
                {else}
                <div class="Box BoxId{$item.product_id}" id="BoxId{$item.product_id}">
                    {if $optionQty > 0}
                    <a href="{modurl modname="ZSELEX" type="user" func="productview" id=$item.product_id}" class="btn buy-btn">{gt text="BUY"}</a>
                    {else}
                    {gt text='Out Of Stock!'}
                    {/if}
                </div>
                {/if}
                <span id="addloader{$item.product_id}"></span>
                {/if}
            </div>
        </div>
        {/foreach}
    </div>
</div>
</div>
{/if}