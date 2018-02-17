
<div class="row">
    <div class="col-md-12">
        {include file="user/cartmenu.tpl"}
        <h2>{gt text='Order Summary'}</h2>
        <h2>{gt text='Your Order Id'} : {$order_id}</h2>
        <h3>{gt text='Congratulations on your order has gone through - you will receive an order confirmation by email. If you have not received it within 10 minutes, please contact us'}</h3>
        {if $payment_type eq 'directpay'}

        {$directpay.info}
        {/if}  

        <div class="city-box">
            <span>{gt text='From Seller'}  :</span><a href="#">{$ownername}</a>
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
                {foreach  from=$orderDetails  key=k1 item=v1}  
                <tr>
                    <td class="product-td" data-th="Product">
                        <div class="clearfix">
                            <div class="col-sm-3">
                                <div class="product-image-box">
                                    <a href="{modurl modname="ZSELEX" type="user" func="productview" id=$v1.product_id}">
                                       {assign var="imagepath" value="zselexdata/`$shop_id`/products/thumb/`$v1.prd_image`"}
                                       {if file_exists($imagepath)  && $v1.prd_image neq ''} 
                                       <img src="zselexdata/{$shop_id}/products/thumb/{$v1.prd_image}" alt="{$v1.product_name}" class="img-responsive"/>
                                        {else}
                                        <img alt="{$v1.product_name}"  src="{$themepath}/images/no-image.jpg"  width="100" class="img-responsive"/>
                                        {/if} 
                                    </a>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <h4 class="product-name-head">{$v1.product_name|cleantext}{if $v1.outofstock}<font color="red">&nbsp;***</font>{/if}</h4>
                                <p>{$v1.prd_description|cleantext}</p>

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
                        {$v1.quantity}
                    </td>
                    <td class="subtotal-td" data-th="Subtotal">DKK {displayprice amount=$v1.total}</td>
                    <td class="actions" data-th="">

                    </td>
                </tr>

                {/foreach}

            </tbody>
            <tfoot>
                <tr class="visible-xs">
                    <td class="text-center total-td">
                        <p>{gt text='Shipping'} = {if $shippingVal < 1}{gt text='Free'}{else}DKK {displayprice amount=$shippingVal}{/if}</p>
                        <p>{gt text='Vat'} = {if $vat > 0}DKK {displayprice amount=$vat}<br />{/if}</p>
                        <strong>{gt text='Total'} = {displayprice amount=$grand_total_all}</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$shop_id}" class="btn btn-warning cs-btn btn-info"><i class="fa fa-angle-left"></i> {gt text='Continue Shopping'}</a>
                        {if $cartCount}   
                        <a href="{modurl modname="ZSELEX" type="user" func="cart"}" class="btn btn-gray back-btn"><i class="fa fa-arrow-circle-left"></i> {gt text='Payment next shop'}</a>
                        {/if}
                        {*<button class="btn btn-default refresh-btn"><i class="fa fa-refresh"></i></button>*}
                    </td>
                    <td colspan="2" class="hidden-xs total-td-desk-head">
                        <p>{gt text='Shipping'} =</p>
                       {if $vat > 0} <p> {gt text='Vat'} = </p> {/if}
                        <p> {gt text='Total price'}  =  </p>
                    </td>
                    <td class="hidden-xs total-td-desk">
                        <p> {if $shippingVal < 1}{gt text='Free'}{else}DKK {displayprice amount=$shippingVal}{/if}</p>
                       {if $vat > 0} <p>  DKK {displayprice amount=$vat}</p>{/if}
                        <p>  <strong> DKK {displayprice amount=$grand_total_all}</strong> </p>
                    </td>
                    <td>


                    </td>
                </tr>
            </tfoot>
        </table>



        {*</div>*}

    <!-- END CART WRAP 01 -->


</div>
</div>