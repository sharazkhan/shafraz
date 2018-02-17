

{* Purpose of this template: Display products within an external context *}
<dt>

<div id="blockcontent"  style="width:370px; height:auto; display:table-cell;">
    <dl>
        {foreach item='item' from=$products}
        <div style="float:left; width:170px; margin-left:15px; display:table-cell;">
            <dt class="P1">
            <a href='http://{$item.domain}/index.php?main_page=product_info&products_id={$item.products_id}' target='_blank'> <img src="http://{$item.domain}/images/{$item.products_image}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'   {/if}/></a>
            </dt>
            <div class="P1T">
                <span class="phd">{$item.manufacturers_name}</span>
                <br>
                <span class="phd1">{$item.products_name}...</span>
            </div>
            <div class="P1TBox">
                <span class="amount"><b>DKK</b> {$item.PRICE}</span>
                <div class="add">
                    <a href="javascript:document.cart_quantity{$item.products_id}.submit()"  class="addbutton"><img src="{$baseurl}themes/KeepRunning/images/add.jpg" />
                        <span><img src="{$baseurl}themes/KeepRunning/images/mouseoverAdd1.png"><p>Tilf?j til kurv</p></span>
                    </a>
                    <form name="cart_quantity{$item.products_id}" action="http://{$item.domain}/index.php?main_page=product_info&action=add_product&products_id={$item.products_id}" method="post" enctype="multipart/form-data" target="_blank"><input type="hidden" name="cart_quantity" value="1" /><input type="hidden" name="products_id" value="{$item.products_id}" /></form>
                </div>
            </div>
        </div>
        {foreachelse}
        <dt> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gt text='No entries found.'}</dt>
        {/foreach}
    </dl>
</div>
</dt>







