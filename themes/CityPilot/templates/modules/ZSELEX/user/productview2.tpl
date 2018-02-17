{pageaddvar name='javascript' value='modules/ZSELEX/javascript/product_user.js'} 
<script>
jQuery(document).ready(function() {
    jQuery("img.lazy").lazyload();
});
</script>
<div class="ContentLeft left">
             <div class="DetailProductImage">
              <img class="lazy" src="{$baseurl}modules/ZSELEX/images/grey_small.gif" data-original="{$baseurl}zselexdata/{$shop_id}/products/medium/{$product.prd_image}" {*width="100%"*} />
             </div>
             
             <!--<ul class="ImagePrivew">
                 <li><img src="{$themepath}/images/Preview1.png" width="98" height="98" /></li>
                 <li><img src="{$themepath}/images/Preview2.png" width="98" height="98" /></li>
                 <li><img src="{$themepath}/images/Preview3.png" width="98" height="98" /></li> 
                 <li><img src="{$themepath}/images/Preview4.png" width="98" height="98" /></li> 
             </ul>-->
 </div>
             
       
<div class="ContentRight left">
          <div class="DetailedProductRight">
              {if $perm}
              <div class="OrageEditSec EditEvent">
                  <a href="{modurl modname="ZSELEX" type="admin" func="products" shop_id=$smarty.request.shop_id product_id=$product.product_id src='detail'}">
                      <img src="{$themepath}/images/OrageEdit.png">{gt text='Edit Product'}
                  </a>
              </div>
              {/if}
              <h4>{$product.product_name|cleantext}</h4>
              <h5>{displayprice amount=$product.prd_price} DKK </h5>

              <p>
                  {*
                  Skønt, blødt ruskind med kontrastfarvet syninger og snører, hvilket giver en moderne klassiker en sporty drejning. Et tekstilfor med 
                  fugtabsorberende egenskab, holder fødderne kølige og sikrer vedvarende komfort. Yderst åndbar og udtagelig tekstilindlægssål med ECCO Comfort Fibre System, sikrer optimal komfort. Forsynet med en fremragende, afbalanceret sprøjtestøbt sål, der er designet til at være let og holdbar.
              *}
              {$product.prd_description|cleantext}
              </p>

              <div class="DetailedProductRightForm">
                
                  {if $option_count > 0}<b>{gt text='Available Options'}:</b><br>{/if}
                   {foreach from=$product_options item='item' key='key'}
                       <div>
                         <span id="label-{$item.product_to_options_id}" class='option_name label-{$item.product_to_options_id}'>{$item.option_name} : </span><br>
                         
                           {if $item.option_type eq 'radio'}
                               {foreach from=$item.values item='value'}
                                   {if $value.option_value_id > 0}
                                        <div>
                                 <input mytype="radio" valueid="{$value.product_to_options_value_id}" id="test-{$item.product_to_options_id}" name="{$item.option_name}" class='options' name="product_options[{$item.option_name}][]" value="{$value.option_value_id}" {if $item.parent_option_id>0}linked="1"{else}linked="0"{/if} type="radio" onClick="changePrice('{$product.product_id}','{$item.option_id}',this.value,'{if $item.parent_option_id>0}1{else}0{/if}',0,'','{$item.parent_option_id}');">{$value.option_value}{if $item.parent_option_id<1}&nbsp;{if $value.price > 0 || $value.price < 0}({if $value.price > 0}+{/if}{displayprice amount=$value.price} DKK){/if}{/if}
                                           </div>
                                  {/if}
                               {/foreach}  
                           {elseif $item.option_type eq 'dropdown'}
                                  <select {if $item.parent_option_id>0}linked="1"{else}linked="0"{/if} mytype="dropdown" id="test-{$item.product_to_options_id}" class='options' name="{$item.option_name}" onChange="changePrice('{$product.product_id}','{$item.option_id}',this.value,'{if $item.parent_option_id>0}1{else}0{/if}',0,'','{$item.parent_option_id}');">
                                           <option value=''>{gt text='select'}</option>
                                {foreach from=$item.values  item='value'}              
                                           <option value="{$value.option_value_id}" valueid="{$value.product_to_options_value_id}">{$value.option_value}{if $item.parent_option_id<1}{if $value.price > 0 || $value.price < 0}&nbsp;({if $value.price > 0}+{/if}{displayprice amount=$value.price} DKK){/if}{/if} </option>
                                {/foreach}  
                                  </select>
                           {elseif $item.option_type eq 'checkbox'}
                                 {foreach from=$item.values  item='value'}
                                     <div>
                                 <input {if $item.parent_option_id>0}linked="1"{else}linked="0"{/if} mytype="checkbox" valueid="{$value.product_to_options_value_id}"  id="test-{$item.product_to_options_id}" class='options' name="{$item.option_name}"  value="{$value.option_value_id}" type="checkbox" onClick="changePrice('{$product.product_id}','{$item.option_id}',this.value,'{if $item.parent_option_id>0}1{else}0{/if}',0, 'checkbox' , '{$item.parent_option_id}');">{$value.option_value}&nbsp;{if $value.price > 0 || $value.price < 0}&nbsp;({if $value.price > 0}+{/if}{displayprice amount=$value.price} DKK){/if}
                                     </div>
                                 {/foreach}  
                           {/if}
                                   
                                   
                               
                                   <!-- Parent Options  -->
                                  
                                   {if $item.parent_option_id > 0}
                                       <br><br>
                                         {getParentProductToOptions parent_option_id=$item.parent_option_id product_id=$product.product_id}
                                           <input type="hidden" id="parentTypeId" value="test-{$parent_product_options.0.parent_option_value_id}">
                                         <span id="label-{$parent_product_options.0.parent_option_value_id}" class='option_name label-{$parent_product_options.0.parent_option_value_id}'>{$parent_product_options.0.option_name} : </span><br>
                                           {*<input type="hidden" id="parentTypeId" value="test-{$parent_product_options.0.parent_option_value_id}">
                                           <span id="label-{$parent_product_options.0.parent_option_value_id}" class='option_name label-{$parent_product_options.0.parent_option_value_id}'>{$parent_product_options.0.option_name} : </span><br>
                                          {if $parent_product_options.0.option_type eq 'dropdown'}
                                           <select linked="1" disabled parent="1" mytype="dropdown" id="test-{$parent_product_options.0.parent_option_value_id}" class='options parents' name="{$parent_product_options.0.option_name}" onChange="changePrice('{$product.product_id}','{$parent_product_options.0.option_id}',this.value ,0,1,'');">
                                           <option value=''>{gt text='select'}</option>
                                            {foreach from=$parent_product_options  item='pitem'}              
                                                       <option value="{$pitem.parent_option_value_id}" valueid="{$pitem.parent_option_value_id}">{$pitem.option_value}</option>
                                            {/foreach}  
                                           </select>
                                           
                                           {elseif $parent_product_options.0.option_type eq 'radio'}
                                            {foreach from=$parent_product_options  item='pitem'}              
                                                <input linked="1" disabled mytype="radio" valueid="{$pitem.parent_option_value_id}" id="test-{$pitem.parent_option_value_id}" name="product_options[{$pitem.option_name}][]"  class='options parents'  value="{$pitem.parent_option_value_id}" type="radio" onClick="changePrice('{$product.product_id}','{$parent_product_options.0.option_id}',this.value ,0,1,'');">{$pitem.option_value}        
                                            {/foreach}  
                                           {/if}*}
                                      <span class="parentDisplay">
                                            {include file="ajax/showParentOptionValues.tpl"}
                                      </span>
                                           
                                            <div class='showPrice'></div>
                                           
                                     {/if}
                                   
                                     
                                   <!-- Parent Options Ends -->
                                  </div>
                                     <br>
                   {/foreach}
                   <br>
                
                  {*<input type="button" value="{gt text='Add to Cart'}" id='OrangeBtn' onclick="document.cart_quantity{$product.product_id}.submit();"/>*}
                   {if !$no_payment}
                   {product_option_exist product_id=$product.product_id}
                    {if !$optionExist} 
                         {if $product.prd_quantity > 0}
                  <input class="BoxId{$product.product_id}" type="button" value="{gt text='Add to Cart'}" id='OrangeBtn' onclick="addToCartOptions('{$product.product_id}','{$smarty.request.shop_id}','{$loggedIn}',1);"/>
                  <span id="addloader{$product.product_id}"></span>
                         {else}
                               {gt text=' Out Of Stock!'}
                         {/if}
                     {else}
                            {if $optionQty > 0}
                  <input class="BoxId{$product.product_id}" type="button" value="{gt text='Add to Cart'}" id='OrangeBtn' onclick="addToCartOptions('{$product.product_id}','{$smarty.request.shop_id}','{$loggedIn}',1);"/>
                  <span id="addloader{$product.product_id}"></span> 
                            {else}
                                 {gt text=' Out Of Stock!'}
                            {/if}
                     {/if}    
                 {* <form name="cart_quantity{$product.product_id}" action="{modurl  modname='ZSELEX' type='user' func='cart'}"  method="post">
                        <input type="hidden" name="cart_quantity" value="1" />
                        <input type="hidden" name="product_id" value="{$product.product_id}" /> 
                        <input type="hidden" name="productName" value="{$product.product_name|cleantext}">
                        <input type='hidden' name='product_price' value="{$product.prd_price}" >   
                        <input type='hidden' name='productImage' value="{$product.prd_image}" >
                        <input type='hidden' name='productDesc' value="{$product.prd_description|cleantext}" >
                        <input type='hidden' name='shop_id' value="{$product.shop_id}" >
                   </form>*}
                   {/if}

              </div>
                   <br>
             <div>
                 
               {assign var="prod_image" value="`$baseurl`zselexdata/`$shop_id`/products/medium/`$product.prd_image`"}
               <span>{fblikeservice action='like' url=$product_link  width="500px" height="21px" layout='horizontal' shop_id=$product.shop_id  addmetatags=true metatitle=$product.product_name metatype="website" metaimage=$prod_image description=$product.prd_description faces=true}</span>
              {* <span>{fbpostonwall shop_id=$product.shop_id  link=$product_link image=$prod_image title=$product.product_name caption='' description=$product.prd_description}</span>*}
               <span>{fbshare shop_id=$product.shop_id  url=$product_link}</span>
             </div>
          </div>

      </div>
             
             