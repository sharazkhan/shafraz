

{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}

 {if $perm}
      {if $servicePerm gt 0}
<table class="edit" style="padding-top:17px;padding-left:20px">
    <tr>
        <td>
    {if $vars.displayinfo eq 'yes'}
      <a class="infoclass"  id="miniSiteProductsInfo" href="{modurl modname='ZSELEX' type='info' func='displayInfo' bid=$bid}" title="{$info.title}">
          <img  src="{$baseurl}images/icons/extrasmall/info.png">
      </a>
    {/if}
       </td>
       <td>&nbsp;</td>
        <td>
    <a href="{modurl  modname='ZSELEX' type='admin' func='viewZenShop' shopId=$smarty.request.shopId}">
         <img title="{gt text=Manage}"  src="{$baseurl}images/icons/extrasmall/configure.png">
    </a>
        </td>
   </tr>
</table>
      {/if}
   {/if}
   
   
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
    height: 45px; margin-left:5px;

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
   
{* Purpose of this template: Display products within an external context *}
<dt>
<div style="clear:both"></div>
{if $shoptype eq 'iSHOP'}
<div id="blockcontent"  style="width:400px; height:auto; display:table-cell;">
    <dl>
        {foreach item='item' from=$products}
        <div style="float:left; width:170px; margin-left:15px; display:table-cell;">
            <dt class="P1">
            {if $item.theme neq ''}
           {* <a href='{$baseurl}index.php?{if $item.theme neq ''}theme={$item.theme}&{/if}module=zselex&type=user&func=productview&id={$item.productId}' target='_blank'> <img src="{$baseurl}modules/ZSELEX/images/products/thumbs/{$item.prdImage}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'   {/if}/></a> *}
         <a target="_blank" href="{modurl  modname='ZSELEX' type='user' func='productview' id=$item.productId}"><img src="{$baseurl}modules/ZSELEX/images/products/thumbs/{$item.prdImage}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'  {/if}/></a>
           {else}
         <a target="_blank" href="{modurl  modname='ZSELEX' type='user' func='productview' id=$item.productId}"><img src="{$baseurl}modules/ZSELEX/images/products/thumbs/{$item.prdImage}"  {if $item.H  neq  ''} height='{$item.H}'  width='{$item.W}' {else} width='170px'  {/if}/></a>
           {/if}
            </dt>
            <div class="P1T">
                <span class="phd">{$item.prdName}</span>
                <br>
                <span class="phd1">{$item.prdDescription}...</span>
            </div>
            <div class="P1TBox">
                <span class="amount"><b>DKK</b> {$item.prdPrice}</span>

                <div class="add">
                    <a href="javascript:document.cart_quantity{$item.productId}.submit()"  class="addbutton"><img src="{$baseurl}themes/KeepRunning/images/add.jpg" />
                        <span><img src="{$baseurl}themes/KeepRunning/images/mouseoverAdd1.png"><p>Tilf?j til kurv</p></span>
                    </a>
                    <form name="cart_quantity{$item.productId}" action="{modurl  modname='ZSELEX' type='user' func='cart'}" {*action="{$baseurl}index.php?module=zselex&type=user&func=cart"*} method="post" enctype="multipart/form-data" target="_blank"><input type="hidden" name="cart_quantity" value="1" /><input type="hidden" name="productId" value="{$item.productId}" /> <input type="hidden" name="productName" value="{$item.prdName}"><input type='hidden' name='product_price' value="{$item.prdPrice}" >   <input type='hidden' name='productImage' value="{$item.prdImage}" > <input type='hidden' name='productDesc' value="{$item.prdDescription}" ></form>
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
<div style="clear:both"></div>
<div id="blockcontent"  style="width:400px; height:auto; display:table-cell;">
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


{else}
    
     <dt> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{gt text='No products found.'}</dt> 

    
{/if}  
    


<script type="text/javascript">
 var defwindowajax = new Zikula.UI.Window($('miniSiteProductsInfo'),{resizable: true });
  var $curr = jQuery(".edit");
      $curr = $curr.prev();
      $curr.css("width", "auto");
      $curr.css("float", "left");
      $curr.css("padding-top", "2px");
      $curr.css("background-position", "1px 2px");
 </script>


