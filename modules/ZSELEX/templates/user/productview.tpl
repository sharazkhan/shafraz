
{pageaddvar name='javascript' value="$themepath/javascript/product_user.js?v=1.1"}  


{if $perm}
<a href="{modurl modname="ZSELEX" type="admin" func="products" shop_id=$smarty.request.shop_id product_id=$product.product_id src='detail'}" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>
    {gt text="Edit Product"}
</a>
{/if}

{setdiscount value=$product.discount orig_price=$product.original_price product_id=$product.product_id}
<div class="clearfix product-details-wrapper">
    <div class="col-sm-6 btm-product-details hover-border">
        <div class="thumbnail text-center">
            {*<a href="#">*}
                <div class="pro-image-product">
                    <a title="Image title here" href="{$baseurl}zselexdata/{$shop_id}/products/fullsize/{$product.prd_image}" rel="gallery1" class="fancybox">
                    {if $is_discount}<span class="offer-pop">-{$dicount_value}</span>{/if}
                        <img src="{$baseurl}zselexdata/{$shop_id}/products/medium/{$product.prd_image}" class="img-responsive" alt="">
                    </a>
                </div>
           {* </a>*}
        </div>
    </div>
    <input type="hidden" id="origPrice" value="{displayprice amount=$product.prd_price}">
    <input type="hidden" id="totPrice" value="">
    <div class="col-sm-6">
        <h2><b>{$product.product_name|cleantext|nl2br}</b></h2>
        {if $is_discount}
        <h5><del>{displayprice amount=$product.original_price} DKK</del></h5>
        {/if}
        <h3>
            <b>
                {if $is_discount}
                {displayprice amount=$dicount_price} DKK
                {else}
                {displayprice amount=$product.prd_price} DKK 
                {/if}
            </b>
        </h3>
        <p> {$product.prd_description|cleantext|nl2br}</p>

        <div class="buy-section">
            {if $option_count > 0}  <p><b>{gt text='Available Options'}:</b></p>{/if}
            {foreach from=$product_options item='item' key='key'}
            <span id="label-{$item.product_to_options_id}" class='option_name label-{$item.product_to_options_id}'>{$item.option_name} : </span><br>
            {if $item.option_type eq 'radio'}
            <div class="form-group">
                {foreach from=$item.values item='value'}
                {if $value.option_value_id > 0}
                <input mytype="radio" price="{$value.price}" valueid="{$value.product_to_options_value_id}" id="test-{$item.product_to_options_id}" name="{$item.option_name}" class='options_select' name="product_options[{$item.option_name}][]" value="{$value.option_value_id}" {if $item.parent_option_id>0}linked="1"{else}linked="0"{/if} type="radio" onClick="changePrice('{$product.product_id}','{$item.option_id}',this.value,'{if $item.parent_option_id>0}1{else}0{/if}',0,'','{$item.parent_option_id}');">{$value.option_value}{if $item.parent_option_id<1}&nbsp;{if $value.price > 0 || $value.price < 0}({displayprice amount=$value.price} DKK){/if}{/if}
                {/if}
                {/foreach} 
            </div>
            {elseif $item.option_type eq 'dropdown'}
            <div class="form-group productview-dropdown">
                <select {if $item.parent_option_id>0}linked="1"{else}linked="0"{/if} mytype="dropdown" name="{$item.option_name}" class="chosen-product-select form-control options_select" id="test-{$item.product_to_options_id}" style="width:252px;" onChange="changePrice('{$product.product_id}','{$item.option_id}',this.value,'{if $item.parent_option_id>0}1{else}0{/if}',0,'','{$item.parent_option_id}');">
                    <option value=''>{gt text='select'}</option>
                    {foreach from=$item.values  item='value'}              
                    <option value="{$value.option_value_id}" price="{$value.price}" valueid="{$value.product_to_options_value_id}">{$value.option_value}{if $item.parent_option_id<1}{if $value.price > 0 || $value.price < 0}&nbsp;({displayprice amount=$value.price pref=true} DKK){/if}{/if} </option>
                    {/foreach}  
                </select>
            </div>
            {elseif $item.option_type eq 'checkbox'}
            <div class="form-group">
                {foreach from=$item.values  item='value'}
                <input {if $item.parent_option_id>0}linked="1"{else}linked="0"{/if} mytype="checkbox" price="{$value.price}" valueid="{$value.product_to_options_value_id}"  id="test-{$item.product_to_options_id}" class='options_select' name="{$item.option_name}"  value="{$value.option_value_id}" type="checkbox" onClick="changePrice('{$product.product_id}','{$item.option_id}',this.value,'{if $item.parent_option_id>0}1{else}0{/if}',0, 'checkbox' , '{$item.parent_option_id}');">{$value.option_value}&nbsp;{if $value.price > 0 || $value.price < 0}&nbsp;({displayprice amount=$value.price} DKK){/if}
                {/foreach}  
            </div>
            {/if}

            {if $item.parent_option_id > 0}

            {getParentProductToOptions parent_option_id=$item.parent_option_id product_id=$product.product_id}
            <input type="hidden" id="parentTypeId" value="test-{$parent_product_options.0.parent_option_value_id}">
            <span id="label-{$parent_product_options.0.parent_option_value_id}" class='option_name label-{$parent_product_options.0.parent_option_value_id}'>{$parent_product_options.0.option_name} : </span><br>

            <span class="parentDisplay">
                {include file="ajax/showParentOptionValues.tpl"}
            </span>

            <div class='showPrice'></div>

            {/if}


            {/foreach}   

            {if $product.enable_question > 0}
            <input type="hidden" id="ques_validate" value="{$product.validate_question}">
            <div class="form-group">
                <div class="quesLabel">{$product.prd_question}</div>
                <div><input type="text" id="ques_ans" value=""></div>
            </div>
            {/if}
            <br>
            {displayquantitydiscount product_id=$product.product_id}

            <div class="form-group">
                {if !$no_payment}
                    {product_option_exist product_id=$product.product_id}
                    {if !$optionExist} 
                        {if $product.prd_quantity > 0}
                            {if $product.prd_price > 0}
                            <input type="button" id="OrangeBtn" value="{gt text='Add to Cart'}" class="btn btn-primary btn-detail BoxId{$product.product_id}" onclick="addToCartOptions('{$product.product_id}','{$smarty.request.shop_id}','{$loggedIn}',1);">
                            {/if}
                        {else}
                            {gt text='Out Of Stock!'}
                        {/if}    

                    {else}
                        {if $optionQty > 0}
                            {if $product.prd_price > 0}
                            <input type="button" id="OrangeBtn" value="{gt text='Add to Cart'}" class="btn btn-primary btn-detail BoxId{$product.product_id}" onclick="addToCartOptions('{$product.product_id}','{$smarty.request.shop_id}','{$loggedIn}',1);">   
                            {/if}
                        {else} 
                             {gt text=' Out Of Stock!'}
                        {/if}   


                    {/if}   
                     <span id="addloader{$product.product_id}"></span> 

                {/if}
            </div>

            <!-- social share  -->
              {assign var="prod_image" value="`$baseurl`zselexdata/`$shop_id`/products/medium/`$product.prd_image`"}
            <div class="social-share">
                         <span>{fblikeservice action='like' url=$product_link  width="500px" height="21px" layout='horizontal' shop_id=$product.shop_id  addmetatags=true metatitle=$product.product_name metatype="website" metaimage=$prod_image description=$metaContent faces=true}</span>
           
               <span>{fbshare shop_id=$product.shop_id  url=$product_link}</span>
      
                 </div>
            <!-- social end -->
        </div>
    </div>
</div>