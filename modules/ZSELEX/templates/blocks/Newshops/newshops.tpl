<input type="hidden" id="shopfrontorder" value="{$modvars.ZSELEX.shoporderby}">
<input type="hidden" id="shopfrontlimit" value="{$modvars.ZSELEX.shopfrontlimit}">
<div class="col-md-12">
                    <h3>{gt text='New Shops'}</h3>
                </div>
<div id="newShopBlock">
 {include file="ajax/newshops.tpl"}
</div>

