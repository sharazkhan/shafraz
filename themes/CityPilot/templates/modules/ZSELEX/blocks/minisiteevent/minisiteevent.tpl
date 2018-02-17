   <style>
    .ImageThumbSec{
        text-align:center; 
        height: 200px; 
        width: 220px; 
        margin-bottom:8px;
       

    }
</style>
<script>
jQuery(document).ready(function() {
    jQuery("img.lazy").lazyload();
});
</script>
 {if $perm}
 <div class="OrageEditSec EditEvent"><a href="{modurl modname="ZSELEX" type="admin" func="events" shop_id=$smarty.request.shop_id}"><img src="{$themepath}/images/OrageEdit.png">{gt text='Edit Events'}</a></div>
 {/if}
<div id="minisiteimage_block">
    {if $count > 0}
    <h3 class="EventName">{gt text='Event'}:</h3>
    {/if}
    <div class="bodyImage">
        {foreach item='item' key=index from=$events}
            {if $index eq 1}
            {break}
            {/if}
        {if $item.showfrom eq 'image'} 
        <a {if $item.event_link!='' && $item.call_link_directly eq 1}{if $item.open_new}target="_blank"{/if}{/if} href="{if $item.event_link!='' && $item.call_link_directly eq 1}{$item.event_link}{else}{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$smarty.request.shop_id eventId=$item.shop_event_id}{/if}">
           {* <div class="ImageThumbSec"  style="background: url({$baseurl}zselexdata/{$ownername}/events/medium/{$item.event_image|replace:' ':'%20'}) no-repeat center center;">
            </div>*}
            {assign var="image1" value=$item.event_image|replace:' ':'%20'}
            {assign var="image" value="zselexdata/`$shop_id`/events/medium/`$image1`"}
            {if file_exists($image)}
             <img class="lazy" style="width:220px" data-original="{$baseurl}zselexdata/{$shop_id}/events/medium/{$item.event_image|replace:' ':'%20'}" >
             {/if}
        </a>
        {elseif $item.showfrom eq 'doc'} 
        {if $item.extension eq 'pdf'}
        <a href="{modurl modname="ZSELEX" type='user' func='pdfViewEvent' shop_id=$smarty.request.shop_id pdf=$item.event_doc}" target="_blank">
       {* <img src="{$themepath}/images/pdf.png" >*}
        <img class="lazy" data-original="{$baseurl}zselexdata/{$shop_id}/events/docs/thumb/{$item.pdf_image}.jpg">
        </a>
        {else if $item.extension eq 'doc'}
        <img src="{$themepath}/images/doc.png" > 
        {/if}

        {elseif $item.showfrom eq 'product'}
        {if $shoptype eq 'iSHOP'}
          
        <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$item.product_id}" target="_blank">
           {* <div class="ImageThumbSec"  style="background: url({$baseurl}zselexdata/{$ownername}/products/thumb/{$item.ishopProduct.prd_image|replace:' ':'%20'}) no-repeat center center;">
            </div>*}
             <img class="lazy" style="width:220px" data-original="{$baseurl}zselexdata/{$shop_id}/products/thumb/{$item.ishopProduct.prd_image|replace:' ':'%20'}" >
        </a>
        {elseif $shoptype eq 'zSHOP'}
        <a href='http://{$zencart.domain}/index.php?main_page=product_info&products_id={$product.products_id}' target='_blank'>
            <div class="ImageThumbSec"  style="background: url(http://{$zencart.domain}/images/{$item.zshopProduct.products_image|replace:' ':'%20'}) no-repeat center center;">
            </div>
        </a>
        {/if}   

        {elseif $item.showfrom eq 'article'}  
        <div class="ImageThumbSec"  style="background: url({$baseurl}{$modvars.News.picupload_uploaddir}/pic_sid{$item.article.sid}-0-norm.jpg) no-repeat center center;">
        </div>

        {/if}
        <br>{$item.shop_event_name}
        {/foreach}
    </div>
    
    {if $count > 0}
    <div align="center" class="viewAllEvent">
   <a href="{modurl modname='ZSELEX' type='user' func='showEvents' shop_id=$smarty.request.shop_id}"> {gt text='view all'} </a>
   </div>
    {/if}
</div>
 <!-- end -->
    


