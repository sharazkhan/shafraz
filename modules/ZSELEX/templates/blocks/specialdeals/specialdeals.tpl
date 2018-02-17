
<input type='hidden' id='sd_adtype' value='{$vars.sd_adtype}'>
<input type='hidden' id='sd_amount' value='{$vars.sd_amount}'>

<input type='hidden' id='sd_articlead_val' value=''>
<input type='hidden' id='sd_productad_val' value=''>

<input type='hidden' id='highad_amount' value='{$vars.highad_amount}'>
<input type='hidden' id='midad_amount' value='{$vars.midad_amount}'>
<input type='hidden' id='lowad_amount' value='{$vars.lowad_amount}'>
<input type='hidden' id='event_amount' value='{$vars.event_amount}'>
<input type='hidden' id='article_amount' value='{$vars.article_amount}'>



<div class="col-md-8 contents-left">
    <h2>{gt text='Special Deals'}</h2>
    <div id="specialdeal_block">
    <div class="highad-products" id="specialdeal_block_products">
         {include file="ajax/productadhigh.tpl"} 
    </div> <!-- high ad ends here ->

    <!-- 4 thumb row -->
    <div class="thumb-row">
        <div class="thumbslider clearfix">
            <div class="midad-products" id="specialdeal_block_products_mid"> <!-- mid ad starts -->
                 {include file="ajax/productadmid.tpl"}
            </div> <!-- mid ad products ends -->

        </div>
    </div>
    <div class="thumb-row">
        <div class="thumbslider clearfix">
            <div class="midad-products" id="specialdeal_block_Events"> <!-- mid ad starts -->
                 {include file="ajax/specialdealevents.tpl"}
            </div> <!-- mid ad products ends -->

        </div>
    </div>
    <div class="thumb-row">
        <div class="thumbslider clearfix">
            <div class="midad-products" id="specialdeal_block_products_low"> <!-- mid ad starts -->
                 {include file="ajax/productadlow.tpl"}
            </div> <!-- mid ad products ends -->

        </div>
    </div>
</div>          
    <!-- 4 thumb row end -->
</div> 