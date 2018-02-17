

{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
<script type="text/javascript" src="modules/ZSELEX/javascript/jquery.lazyload.js"></script>


<style>
    
.add {float:right; margin-right:10px; margin-top:6px; clear:right}
.add p{padding-top: 5px; padding-bottom:7px; margin-top:11px}
.add a:link{ text-decoration:none}
.addbutton{
    position: relative;
    float: right;
    z-index:0;
}


.addbutton:hover {
    background-color: transparent;
    z-index: 50;
}

.addbutton span{
    position: absolute;
    background-color:none;
    background:none;
    padding: 0px;
    left: -1000px;
    border: 0;
    visibility: hidden;
    color: black;
    text-decoration: none;
}

.addbutton span img {
    border-width: 0;
    padding-left: 40px; 
    padding-top: 2px;
}

.addbutton:hover span{
    visibility: visible;
    top: 24px;
    left: -35px;
}


.addbutton:hover p {
    display: block;
    position: absolute;
    top: -5px;
    left: -12px;
    width: 110px; 
    height: 13px;
    font-size: 11px;
    text-align: center;
    color: #fff;
    background-color: #282b30;
}

.P1 {
    height: 210px; 
    width: 183px;
}
.P1T {
    width: 183px;
    height: 45px; margin-left:13px;

}

.phd {
    color:black; font-weight:bold; font-size:14px;

}

.phd1 {
    color: #666; font-size:11px;

}

.P1TBox {
    width: 169px; clear: left; padding-top: 0px;
    height: 34px; margin-left:auto; margin-right:auto; border: solid 1px #999; padding-left:5px; font-size:15px; font-weight:bold;

}
.amount{margin-top:10px; float:left; margin-left:5px; color:black}



</style>
{if $perm}
{if $vars.displayinfo eq 'yes'}
  <a class="infoclass"  id="miniShopProducts" href="{modurl modname='ZSELEX' type='info' func='displayInfo' bid=$bid}" title="{$info.title}">
      <img  src="{$baseurl}images/icons/extrasmall/info.png">
  </a>
{/if}

{/if}

 <script type="text/javascript">
 var defwindowajax = new Zikula.UI.Window($('miniShopProducts'),{resizable: true });
 </script>


{* Purpose of this template: Display products within an external context *}
<dt>

{if $shoptype eq 'iSHOP'}
<div id="blockcontent"  style="width:auto; height:auto;">
    <dl>
        {foreach item='item' from=$products}
         <div style="float:left; width:180px; padding-left:10px; padding-bottom: 40px;">
            <dt class="P1">
            {if $item.theme neq ''}
           {* <a href='{$baseurl}index.php?{if $item.theme neq ''}theme={$item.theme}&{/if}module=zselex&type=user&func=productview&id={$item.productId}' target='_blank'> <img src="{$baseurl}modules/ZSELEX/images/products/thumbs/{$item.prdImage}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'   {/if}/></a> *}
         <a target="_blank" href="{modurl  modname='ZSELEX' type='user' func='productview' id=$item.productId}"><img class="lazy"  src="{$baseurl}images/grey_small.gif" data-original="{$baseurl}zselexdata/{$ownerName}/products/thumb/{$item.prd_image}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'  {/if}/></a>
           {else}
         <a target="_blank" href="{modurl  modname='ZSELEX' type='user' func='productview' id=$item.productId}"><img class="lazy"  src="{$baseurl}images/grey_small.gif" data-original="{$baseurl}zselexdata/{$ownerName}/products/thumb/{$item.prd_image}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'  {/if}/></a>
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
                    <a href="javascript:document.cart_quantity{$item.product_id}.submit()"  class="addbutton"><img src="{$baseurl}themes/KeepRunning/images/add.jpg" />
                        <span><img src="{$baseurl}themes/KeepRunning/images/mouseoverAdd1.png"><p>Tilf?j til kurv</p></span>
                    </a>
                    <form name="cart_quantity{$item.product_id}" action="{modurl  modname='ZSELEX' type='user' func='cart'}" {*action="{$baseurl}index.php?module=zselex&type=user&func=cart"*} method="post" enctype="multipart/form-data" target="_blank">
                        <input type="hidden" name="cart_quantity" value="1" /><input type="hidden" name="productId" value="{$item.product_id}" /> 
                        <input type="hidden" name="productName" value="{$item.product_name}">
                        <input type='hidden' name='product_price' value="{$item.product_price}" > 
                        <input type='hidden' name='productImage' value="{$item.prd_image}" > 
                        <input type='hidden' name='productDesc' value="{$item.prd_description}" >
                        <input type='hidden' name='shop_id' value="{$item.shop_id}" >
                    </form>
                </div>
            </div>
        </div>

        {foreachelse}
        <dt> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gt text='No Products.'}</dt>
        {/foreach}

    </dl>

</div>
</dt>

{elseif $shoptype eq 'zSHOP'}
  
{* Purpose of this template: Display products within an external context *}
<dt>

<div  id="blockcontent"  style="width:auto; height:auto; ">
    <dl>
        {foreach item='item' from=$products}
        <div style="float:left; width:180px; padding-left:10px; padding-bottom: 40px;">
            <dt class="P1">
            <a href='http://{$item.domain}/index.php?main_page=product_info&products_id={$item.products_id}' target='_blank'> <img class="lazy"  src="{$baseurl}images/grey_small.gif" data-original="http://{$item.domain}/images/{$item.products_image}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'   {/if}/></a>
            </dt>
            <div class="P1T">
                <span class="phd">{$item.manufacturers_name}</span>
                <br>
                <span class="phd1">{$item.products_name}...</span>
            </div>
            <div class="P1TBox">
                <span class="amount"><b>DKK</b> {$item.PRICE}</span>
                <div class="add">
                    <a href="javascript:document.cart_quantity{$item.products_id}.submit()"  class="addbutton"><img src="{$baseurl}themes/KeepRunning/images/add.jpg"/>
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


{else}
    
     <dt> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gt text='No products found.'}</dt> 

    
{/if}  
    


<script type="text/javascript">
  //jQuery.noConflict();
  jQuery(function() {
          jQuery("img.lazy").lazyload({
               // placeholder : document.location.pnbaseURL + "images/grey.gif",
                //threshold : 200,
                effect : "fadeIn"
              
             
          });
      });
     
</script>


