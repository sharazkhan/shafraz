{pageaddvar name='javascript' value="$themepath/javascript/product_user.js?v=1.1"} 
{pageaddvar name='javascript' value="$themepath/javascript/minishop.js"}
<div class="product-list-head clearfix">
    <div class="col-md-7 col-sm-6 product-dropdown-select">
        <div class="dropdown-sub">
            <form  class="z-form" id="cat_filter" id="cat_filter"  action="{modurl modname="ZSELEX" type="user" func="shop" shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
                   <input type="hidden" name="startnum" value="{$startnum}" />
                <input type="hidden" name="submit_category" value="1" />
                <input type="hidden" name="mnfrIds" value="{$manfIds}" />

                <select name='prod_category[]' id="prod_category" onchange="document.forms['cat_filter'].submit();" data-placeholder="{gt text='Select Category'}" multiple class="chosen-select form-control mcategory" tabindex="1">
                    <option value=""></option>
                    {foreach from=$categories  item='item'}
                    <option value="{$item.prd_cat_id}"  {foreach from=$prod_catIdsArr item=itm} {if $item.prd_cat_id eq $itm} selected="selected" {/if} {/foreach} > {$item.prd_cat_name} </option>
                    {/foreach}
                </select>
            </form>
        </div>
        <div class="dropdown-sub">
            <form   class="z-form" id="mnfr_filter"  action="{modurl modname="ZSELEX" type="user" func="shop" shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
                    <input type="hidden" name="startnum" value="{$startnum}" />
                <input type="hidden" name="prod_categorys" value="{$prod_catIds}" />
                <input type="hidden" name="submit_mnfr" value="1" />
                <select name='prod_mnfr[]'  id="prod_mnfr" onchange="document.forms['mnfr_filter'].submit();" data-placeholder="{gt text='Select Manufacturer'}" multiple class="chosen-select form-control mmanuf" tabindex="1">
                    <option value=""></option>
                    {foreach from=$manufacturers  item='manufacturer'}
                    <option value="{$manufacturer.manufacturer_id}" {foreach from=$manfIdsArr item=itm1} {if $manufacturer.manufacturer_id eq $itm1} selected="selected" {/if} {/foreach}   > {$manufacturer.manufacturer_name} </option>
                    {/foreach}
                </select>
            </form>
        </div>
    </div>
    <div class="text-right col-md-5 col-sm-6 product-pagination">
        {*<ul class="pagination">
            <li class="p-arrows disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
            <li class="p-arrows disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&lsaquo;</span></a></li>
            <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">...</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li class="p-arrows"><a aria-label="Next" href="#"><span aria-hidden="true">&rsaquo;</span></a></li>
            <li class="p-arrows"><a aria-label="Next" href="#"><span aria-hidden="true">&raquo;</span></a></li>
        </ul>*}
        {if $total_count > 0}
        {pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages='5'} 
        {/if}
    </div>
</div>
    {if $perm}
<a href="{modurl modname="ZSELEX" type="admin" func="products" shop_id=$smarty.request.shop_id}" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>{gt text='Edit Products'}</a>
{/if}
<div class="clearfix product-list-wrapper">
    {foreach item='item' from=$products}
    {setdiscount value=$item.discount orig_price=$item.original_price product_id=$item.product_id}
    <div class="col-sm-4 col-xs-6 btm-product-list hover-border">
        <div class="thumbnail text-center">
            <a href="{modurl  modname='ZSELEX' type='user' func='productview' id=$item.product_id}">
                {assign var="imagepath" value="zselexdata/`$shop_id`/products/thumb/`$item.prd_image`"}
                 {if $is_discount}
                <span class="offer-pop">-{$dicount_value}</span>
                 {/if}
                <div class="pro-image">
                    {if file_exists($imagepath) && $item.prd_image neq ''} 
                    {imageproportional image=$item.prd_image path="`$baseurl`zselexdata/`$shop_id`/products/medium" height="300" width="410"}
                    <img src="{$baseurl}zselexdata/{$shop_id}/products/medium/{$item.prd_image}" class="img-responsive" alt="" {$imageProperty}>
                    {else}
                    <img class="img-responsive"  src="{$themepath}/images/no-image.jpg"  width="150" height="150"/>
                    {/if}
                </div>
                <div class="btm-product-name clearfix">
                    <h4>{shorttext text=$item.product_name len=22}</h4>
                     {if $is_discount}
                    <h5><del>{displayprice amount=$item.original_price} DKK</del></h5>
                     {/if}
                    <h5><b>
                            {if $is_discount}
                            {displayprice amount=$dicount_price} DKK
                            {else}
                            {displayprice amount=$item.prd_price} DKK
                            {/if}
                        </b></h5>
                </div>
            </a>
            {if !$no_payment}
            {product_option_exist product_id=$item.product_id}
            {if !$optionExist} 
            <div class="Box BoxId{$item.product_id}" id="BoxId{$item.product_id}">
                {if $item.prd_quantity > 0}
                {if $item.prd_price > 0}
                {if $item.enable_question < 1}
                <a id="buytxt{$item.product_id}" href="#" onClick="addToCartOptions('{$item.product_id}', '{$smarty.request.shop_id}', '{$loggedIn}');return false;" class="btn buy-btn">{gt text="BUY"}</a>
                {else}
                <a id="buytxt{$item.product_id}" href="{modurl modname="ZSELEX" type="user" func="productview" id=$item.product_id}" class="btn buy-btn">{gt text="BUY"}</a>  
                {/if}   
                {/if}
                {else}
                {gt text='Out Of Stock!'}
                {/if} 
            </div>
            {else}
            <div class="Box BoxId{$item.product_id}" id="BoxId{$item.product_id}">
                {if $optionQty > 0}
                {if $item.prd_price > 0}
                <a id="buytxt{$item.product_id}" href="{modurl modname="ZSELEX" type="user" func="productview" id=$item.product_id}" class="btn buy-btn">{gt text="BUY"}</a>  
                {/if}
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
<div class="product-list-footer clearfix">
    <div class="col-sm-12  product-pagination">
        {* <ul class="pagination">
            <li class="p-arrows disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
            <li class="p-arrows disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&lsaquo;</span></a></li>
            <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li class="p-arrows"><a aria-label="Next" href="#"><span aria-hidden="true">&rsaquo;</span></a></li>
            <li class="p-arrows"><a aria-label="Next" href="#"><span aria-hidden="true">&raquo;</span></a></li>
        </ul>*}

        {if $total_count > 0}
        {pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages='5'}
        {/if}    


    </div>

</div>