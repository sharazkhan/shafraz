
{* Purpose of this template: Display products within an external context *}
<dt>
<div id="blockcontent"  style="width:380px; height:auto; display:table-cell;">
    <dl>
        {foreach item='item' from=$ishops}
        <div style="float:left; width:170px; margin-left:15px; display:table-cell;">
            <dt class="P1">
            {if $item.theme neq ''}
           {* <a href='{$baseurl}index.php?{if $item.theme neq ''}theme={$item.theme}&{/if}module=zselex&type=user&func=productview&id={$item.product_id}' target='_blank'> <img src="{$baseurl}zselexdata/products/thumbs/{$item.prd_image}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'   {/if}/></a> *}
         <a target="_blank" href="{modurl  modname='ZSELEX' type='user' func='productview' id=$item.product_id}"><img src="{$baseurl}zselexdata/products/thumbs/{$item.prd_image}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'  {/if}/></a>
           {else}
         <a target="_blank" href="{modurl  modname='ZSELEX' type='user' func='productview' id=$item.product_id}"><img src="{$baseurl}zselexdata/products/thumbs/{$item.prd_image}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'  {/if}/></a>
           {/if}
            </dt>
            <div class="P1T">
                <span class="phd">{$item.product_name}</span>
                <br>
                <span class="phd1">{$item.prd_description}...</span>
            </div>
            <div class="P1TBox">
                <span class="amount"><b>DKK</b> {$item.prd_price}</span>

                <div class="add">
                    <a href="javascript:document.cart_quantity{$item.product_id}.submit()"  class="addbutton"><img src="{$themepath}/images/add.jpg" />
                        <span><img src="{$themepath}/images/mouseoverAdd1.png"><p>{gt text='Add to Cart'}</p></span>
                    </a>
                    <form name="cart_quantity{$item.product_id}" action="{modurl  modname='ZSELEX' type='user' func='cart'}" {*action="{$baseurl}index.php?module=zselex&type=user&func=cart"*} method="post" enctype="multipart/form-data" target="_blank">
                        <input type="hidden" name="cart_quantity" value="1" />
                        <input type="hidden" name="product_id" value="{$item.product_id}" /> 
                        <input type="hidden" name="productName" value="{$item.product_name}">
                        <input type='hidden' name='product_price' value="{$item.prd_price}" >   
                        <input type='hidden' name='productImage' value="{$item.prd_image}" > 
                        <input type='hidden' name='productDesc' value="{$item.prd_description}" >
                        <input type='hidden' name='shop_id' value="{$item.shop_id}" >
                        <input type='hidden' name='shopUser' value="{$item.uname}" >
                        <input type='hidden' name='service' value="{$item.type}" >
                    </form>
                </div>
            </div>
        </div>

        {foreachelse}
        <dt> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gt text='No entries found.'}</dt>
        {/foreach}

    </dl>

</div>
</dt>






