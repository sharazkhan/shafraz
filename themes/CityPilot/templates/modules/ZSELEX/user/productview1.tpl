{pageaddvar name='javascript' value='modules/ZSELEX/javascript/product_user.js'} 
<div class="ContentLeft left">
             <div class="DetailProductImage">
              <img src="zselexdata/{$ownername}/products/medium/{$product.prd_image}" {*width="100%"*} />
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
                 {*<select>
                      <option value="Test">Test</option>
                      <option value="Test1">Test1</option>
                      <option value="Test3">Test2</option>
                      <option value="Test4">Test4</option>
                  </select>		&nbsp;&nbsp;<input type="submit" value="submit" />
                  <br /><br />
                  <select>
                      <option value="Test">Test</option>
                      <option value="Test1">Test1</option>
                      <option value="Test3">Test2</option>
                      <option value="Test4">Test4</option>
                  </select>*}
                  {if $option_count > 0}<b>{gt text='Available Options'}:</b></br>{/if}
                   {foreach from=$produc_options item='item' key='key'}
                         <span id="label-{$item.product_to_options_id}" class='option_name'>{$item.option_name} : </span><br>
                         
                           {if $item.option_type eq 'radio'}
                               {foreach from=$item.values item='value'}
                                 <input mytype="radio" valueid="{$value.product_to_options_value_id}" id="test-{$item.product_to_options_id}" name="{$item.option_name}" class='options' name="product_options[{$item.option_name}][]" value="{$item.product_to_options_id},{$value.option_value_id},{$value.price}" type="radio" onClick="changePrice('{$item.option_name}' , this.value);">{$value.option_value}&nbsp;{if $value.price > 0}({displayprice amount=$value.price} DKK){/if}
                                {/foreach}  
                           {elseif $item.option_type eq 'dropdown'}
                                  <select mytype="dropdown" id="test-{$item.product_to_options_id}" class='options' name="{$item.option_name}" onChange="changePrice('{$item.option_name}' , this.value);">
                                           <option value=''>{gt text='select'}</option>
                                {foreach from=$item.values  item='value'}              
                                           <option value="{$item.product_to_options_id},{$value.option_value_id},{$value.price}" valueid="{$value.product_to_options_value_id}">{$value.option_value}&nbsp;{if $value.price > 0}(+{displayprice amount=$value.price} DKK){else}({displayprice amount=$value.price} DKK){/if}</option>
                                {/foreach}  
                                  </select>
                           {elseif $item.option_type eq 'checkbox'}
                                 {foreach from=$item.values  item='value'}
                                 <input mytype="checkbox" valueid="{$value.product_to_options_value_id}"  id="test-{$item.product_to_options_id}" class='options' name="{$item.option_name}"  value="{$item.product_to_options_id},{$value.option_value_id},{$value.price}" type="checkbox" onClick="changePrice('{$item.option_name}' , this.value);">{$value.option_value}&nbsp;{if $value.price > 0}({displayprice amount=$value.price} DKK){/if}
                                 {/foreach}  
                           {/if}
                                   <br> <br>
                   {/foreach}
                
                  {*<input type="button" value="{gt text='Add to Cart'}" id='OrangeBtn' onclick="document.cart_quantity{$product.product_id}.submit();"/>*}
                   {if !$no_payment}
                  <input class="BoxId{$product.product_id}" type="button" value="{gt text='Add to Cart'}" id='OrangeBtn' onclick="addToCartOptions('{$product.product_id}','{$smarty.request.shop_id}','{$loggedIn}',1);"/>
                  <span id="addloader{$product.product_id}"></span>
                  <form name="cart_quantity{$product.product_id}" action="{modurl  modname='ZSELEX' type='user' func='cart'}"  method="post">
                        <input type="hidden" name="cart_quantity" value="1" />
                        <input type="hidden" name="product_id" value="{$product.product_id}" /> 
                        <input type="hidden" name="productName" value="{$product.product_name|cleantext}">
                        <input type='hidden' name='product_price' value="{$product.prd_price}" >   
                        <input type='hidden' name='productImage' value="{$product.prd_image}" >
                        <input type='hidden' name='productDesc' value="{$product.prd_description|cleantext}" >
                        <input type='hidden' name='shop_id' value="{$product.shop_id}" >
                   </form>
                   {/if}

              </div>
                   <br>
             <div>
                 
               {assign var="prod_image" value="`$baseurl`zselexdata/`$ownername`/products/medium/`$product.prd_image`"}
               <span>{fblikeservice action='like' url=$product_link  width="500px" height="21px" layout='horizontal' shop_id=$product.shop_id  addmetatags=true metatitle=$product.product_name metatype="website" metaimage=$prod_image description=$product.prd_description faces=true}</span>
              {* <span>{fbpostonwall shop_id=$product.shop_id  link=$product_link image=$prod_image title=$product.product_name caption='' description=$product.prd_description}</span>*}
               <span>{fbshare shop_id=$product.shop_id  url=$product_link}</span>
             </div>
          </div>

      </div>
             
             