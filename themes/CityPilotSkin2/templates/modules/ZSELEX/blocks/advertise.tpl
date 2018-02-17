<link rel="stylesheet" type="text/css" href="{$stylepath}/productad.css"/>
<input type='hidden' id='adtype' value='{$vars.adtype}'>
<input type='hidden' id='amount' value='{$vars.amount}'>


{* Purpose of this template: Display products within an external context *}

<div class="productad" id="blockadcontent">
    {*
           {foreach item='item' from=$products}
        <div class="productitem">
            <dt class="P1">
              <a href='http://{$item.domainname}/index.php?main_page=product_info&products_id={$item.products_id}' target='_blank'><img src="http://{$item.domainname}/images/{$item.products_image}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'   {/if}/></a>
            </dt>
            <div class="P1T">
                <span class="phd">{$item.manufacturers_name}</span>
                <br>
                <span class="phd1">{$item.products_name}...</span>
            </div>
           <div class="P1TBox">
                <span class="amount"><b>{gt text='DKK'}</b> {$item.PRICE}</span>
                <div class="add">
                    <a href="javascript:document.cart_quantity{$item.products_id}.submit()"  class="addbutton"><img src="{$themepath}/images/add.jpg" />
                        <span><img src="{$themepath}/images/mouseoverAdd1.png"><p>{gt text='Add to Cart'}</p></span>
                    </a>
                    <form name="cart_quantity{$item.products_id}" action="http://{$item.domainname}/index.php?main_page=product_info&action=add_product&products_id={$item.products_id}" method="post" enctype="multipart/form-data" target="_blank"><input type="hidden" name="cart_quantity" value="1" /><input type="hidden" name="products_id" value="{$item.products_id}" /></form>
                </div>

            </div>
       </div>

        {foreachelse}
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gt text='No entries found.'}
        {/foreach}
        *}
</div>








