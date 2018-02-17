<div class="row">
    <div class="col-md-12">
       {include file="user/cartmenu.tpl"}
        <h2>{gt text='Shopping Cart'}</h2>

        <!-- CART WRAP 01 -->
            {foreach  from=$products  key=cart_shop_id item=v}
                 {assign var="outOfStock" value=0}
                 {setsellername value=$products key=$cart_shop_id}
        {*<div class="multi-cart">*}
       {*<form action="" class="shop-cart-list">*}
         <form class="shop-cart-list" name='updatecart{$k}' action='index.php?module=zselex&type=user&func=updateUserCart&shop_id={$cart_shop_id}' method='post'>
            <div class="city-box">
                <span>{gt text='Shop'}  :</span><a href="#">{$shopname}</a>
            </div>

            <table id="cart" class="table table-hover table-condensed cart-table">
                <thead>
                    <tr>
                        <th style="width:42%">{gt text='Product'}</th>
                        <th style="width:16%">{gt text='Price'}</th>
                        <th style="width:16%">{gt text='Quantity'}</th>
                        <th style="width:22%">{gt text='Subtotal'}</th>
                        <th style="width:4%"></th>
                    </tr>
                </thead>
                <tbody>
                     {foreach  from=$products.$cart_shop_id  key=k1 item=v1}  
                    <tr>
                        <td class="product-td" data-th="Product">
                            <div class="clearfix">
                                <div class="col-sm-3">
                                    <div class="product-image-box">
                                         <a href="{modurl modname="ZSELEX" type="user" func="productview" id=$v1.product_id}">
                                        {assign var="imagepath" value="zselexdata/`$cart_shop_id`/products/thumb/`$v1.prd_image`"}
                                          {if file_exists($imagepath)  && $v1.prd_image neq ''} 
                                        <img src="zselexdata/{$cart_shop_id}/products/thumb/{$v1.prd_image}" alt="{$v1.product_name}" class="img-responsive"/>
                                        {else}
                                        <img alt="{$v1.product_name}"  src="{$themepath}/images/no-image.jpg"  width="100" class="img-responsive"/>
                                          {/if} 
                                         </a>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <h4 class="product-name-head">{$v1.product_name|cleantext}{if $v1.outofstock}<font color="red">&nbsp;***</font>{/if}</h4>
                                    <p>{$v1.prd_description|cleantext}</p>
                                    {*<p><b>T-Shirts:</b> <span>S</span></p>*}
                                     {if $v1.cart_content!=''}
                                            
                                            {displayoptions options=$v1.cart_content}
                                        {/if}
                                        {if $v1.enable_question}
                                           
                                             <p>{$v1.prd_question}</p>:
                                           
                                             <input autocomplete="off" name="prd_answer[{$v1.cart_id}+{$v1.product_id}]"  value='{$v1.prd_answer|safetext|cleantext}'>
                                         {/if}
                                </div>
                            </div>
                        </td>
                        <td class="price-td" data-th="Price">DKK {displayprice amount=$v1.price}</td>
                        <td class="quantity-td" data-th="Quantity">
                            <input type="number" name="quantity[{$v1.cart_id}]"  class="form-control text-center" value="{$v1.quantity}" autocomplete="off">
                        </td>
                        <td class="subtotal-td" data-th="Subtotal">DKK {displayprice amount=$v1.final_price}</td>
                        <td class="actions" data-th="">
                            <a href="index.php?module=zselex&type=user&func=deleteUserCart&id={$v1.cart_id}&shop_id={$cart_shop_id}">
                                <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
                            </a>
                        </td>
                    </tr>
                      {displayquantitydiscount product_id=$v1.product_id page='cart'}
                      {if $v1.outofstock}
                                    {assign var="outOfStock" value=1}
                                   {/if}
                     {/foreach}
                    
                </tbody>
                <tfoot>
                    <tr class="visible-xs">
                        <td class="text-center total-td"><strong>{gt text='Total'} {displayprice amount=$GRANDSUM}</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="{modurl modname="ZSELEX" type="user" func="shop" shop_id=$cart_shop_id}" class="btn btn-warning cs-btn btn-info"><i class="fa fa-angle-left"></i> {gt text='Continue Shopping'}</a>
                            <a href="{if $last_shop_id > 0}{modurl modname="ZSELEX" type="user" func="site" shop_id=$last_shop_id}{else}{homepage}{/if}" class="btn btn-gray back-btn"><i class="fa fa-arrow-circle-left"></i>{gt text='Back'}</a>
                            <button class="btn btn-default refresh-btn"><i class="fa fa-refresh"></i></button>
                        </td>
                        <td colspan="2" class="hidden-xs total-td-desk-head">{gt text='Total price'}  = </td>
                        <td class="hidden-xs total-td-desk"><strong> DKK {displayprice amount=$GRANDSUM}</strong></td>
                        <td>
                             {if !$outOfStock}
                            <a href="#" onClick="document.forms['checkout{$cart_shop_id}'].submit()" class="btn btn-primary checkout-btn">{gt text='Checkout'} <i class="fa fa-arrow-right"></i>
                            </a>
                             {/if}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>
        <form name="checkout{$cart_shop_id}" action='{modurl modname="ZSELEX" type="user" func="checkout"}' method='post'>  
             <input type="hidden" name="cart_shop_id" value="{$cart_shop_id}">
        </form>
    {*</div>*}
          {foreachelse}
              <div align="center">  {gt text='No products in cart'} </div>
          {/foreach}       
        <!-- END CART WRAP 01 -->


    </div>
</div>