<div class="DetailImageContainter">
    <div class="DetailImageBlock left">
        <div class="DetailImage">
            <img src="zselexdata/{$ownername}/products/medium/{$product.prd_image}" {*width="100%"*} />
        </div>
       {* <div class="ImagePrevieMain">
            <ul>
                <li><img src="images/skin1/Privew1.png" /></li>
                <li><img src="images/skin1/Privew2.png" /></li>
            </ul>
        </div>*}
    </div>
    <div class="DetailProduct_TextBlock right">
        <h3>{$smarty.request.shop_name} {$product.product_name}t</h3>
        <h4>{$product.prd_price},- dkk</h4>
        <p>
              {$product.prd_description}
            <br /><br /><br />
            {*<select>
                <option value="2Stg">2 Stg</option>
                <option value="3Stg">3 Stg</option>
                <option value="4Stg">4 Stg</option>
                <option value="5Stg">5 Stg</option>
            </select><br /><br />
            <select>
                <option value="Orange">Orange</option>
                <option value="Green">Green</option>
                <option value="red">Bed</option>
                <option value="blue">Blue</option>
            </select><br /><br />*}
            <input style="cursor:pointer" type="button" value="læg i kurv" onclick="document.cart_quantity{$product.product_id}.submit();"/>
            <form name="cart_quantity{$product.product_id}" action="{modurl  modname='ZSELEX' type='user' func='cart'}"  method="post">
                        <input type="hidden" name="cart_quantity" value="1" />
                        <input type="hidden" name="product_id" value="{$product.product_id}" /> 
                        <input type="hidden" name="productName" value="{$product.product_name}">
                        <input type='hidden' name='product_price' value="{$product.prd_price}" >   
                        <input type='hidden' name='productImage' value="{$product.prd_image}" >
                        <input type='hidden' name='productDesc' value="{$product.prd_description}" >
                        <input type='hidden' name='shop_id' value="{$product.shop_id}" >
            </form>
        </p>
    </div>

</div>