
<style>
    #myimages{
        text-align:center; 
        height: 98px; 
        width: 98px; 
        border:solid 1px black; 

    }
</style>

{if $perm}
 <div class="OrageEditSec EditEvent"><a href="{modurl modname="ZSELEX" type="admin" func="events" shop_id=$smarty.request.shop_id}"><img src="themes/CityPilot/images/OrageEdit.png">{gt text='Edit Events'}</a></div>
 {/if}
<div id="minisiteimage_block">
    {if $count > 0}
    <h3 class="Skinh3">{gt text='Event'}:</h3>
    {/if}
    <div class="bodyImage">
        {foreach item='item' key=index from=$events}
            <div class="left">
        {if $item.showfrom eq 'image'} 
        <a href="{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$smarty.request.shop_id eventId=$item.shop_event_id}">
            <div class="ImageThumbSec"  style="background: url({$baseurl}zselexdata/{$ownername}/events/medium/{$item.event_image|replace:' ':'%20'}) no-repeat center center;">
            </div>
        </a>
        {elseif $item.showfrom eq 'doc'} 
        {if $item.extension eq 'pdf'}
            <div class="LinkContainer"><a href="{modurl modname="ZSELEX" type='user' func='pdfViewEvent' shop_id=$smarty.request.shop_id pdf=$item.event_doc}" target="_blank">
        <img src="themes/CityPilot/images/pdf.png" >
        </a></div>
        {else if $item.extension eq 'doc'}
           <div class="LinkContainer"> <a href="{modurl modname="ZSELEX" type='user' func='pdfViewEvent' shop_id=$smarty.request.shop_id pdf=$item.event_doc}" target="_blank">
        <img src="themes/CityPilot/images/doc.png" > 
        </a></div>
        {/if}

        {elseif $item.showfrom eq 'product'}
        {if $shoptype eq 'iSHOP'}
        <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$product.product_id}" target="_blank">
           <div class="ImageThumbSec"  style="background: url({$baseurl}zselexdata/{$ownername}/products/thumb/{$item.ishopProduct.prd_image|replace:' ':'%20'}) no-repeat center center;">
            </div>
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
        {$item.shop_event_name}
          </div>
        {/foreach}
    </div>
   <div class="viewAllEvent">
   <a href="{modurl modname='ZSELEX' type='user' func='showEvents' shop_id=$smarty.request.shop_id}"> {gt text='view all'} </a>
 </div>
</div>
 

