
{if $perm}
<a href="{modurl modname="ZSELEX" type="admin" func="events" shop_id=$smarty.request.shop_id}" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>{gt text='Edit Events'}</a>
{/if}
<div class="shop-event-wrap col-md-12 col-sm-5 col-md-pull-0 col-sm-pull-6 col-xs-5 col-xs-pull-6">
    {if $count > 0}
    <h3>{gt text='Event'}:</h3>
    {/if}
    <div class="shop-event">
        {foreach item='item' key=index from=$events}
            {if $index eq 1}
            {break}
            {/if}
            {if $item.showfrom eq 'image'} 
            <a {if $item.event_link!='' && $item.call_link_directly eq 1}{if $item.open_new}target="_blank"{/if}{/if} href="{if $item.event_link!='' && $item.call_link_directly eq 1}{$item.event_link}{else}{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$smarty.request.shop_id eventId=$item.shop_event_id}{/if}">
                {assign var="image1" value=$item.event_image|replace:' ':'%20'}
                {assign var="image" value="zselexdata/`$shop_id`/events/medium/`$image1`"}
                {if file_exists($image)}
                <img alt="" src="{$baseurl}zselexdata/{$shop_id}/events/medium/{$item.event_image|replace:' ':'%20'}" class="img-responsive">
                {/if}
            </a>
            {elseif $item.showfrom eq 'doc'} 
            {if $item.extension eq 'pdf'}
            <a href="{modurl modname="ZSELEX" type='user' func='pdfViewEvent' shop_id=$smarty.request.shop_id pdf=$item.event_doc}" target="_blank">
               <img class="img-responsive" src="{$baseurl}zselexdata/{$shop_id}/events/docs/thumb/{$item.pdf_image}.jpg">
            </a>
            {else if $item.extension eq 'doc'}
            <img class="img-responsive" src="{$themepath}/images/doc.png" > 
            {/if}
            {elseif $item.showfrom eq 'product'}
                {if $shoptype eq 'iSHOP'}
                <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$item.product_id}" target="_blank">
                     <img class="img-responsive"  src="{$baseurl}zselexdata/{$shop_id}/products/thumb/{$item.ishopProduct.prd_image|replace:' ':'%20'}" >
                </a>
                {elseif $shoptype eq 'zSHOP'}
                <a href='http://{$zencart.domain}/index.php?main_page=product_info&products_id={$product.products_id}' target='_blank'>
                       <img class="img-responsive"  src="http://{$zencart.domain}/images/{$item.zshopProduct.products_image|replace:' ':'%20'}" >
                </a>
                {/if} 
            {elseif $item.showfrom eq 'article'} 
                <img class="img-responsive"  src="{$baseurl}{$modvars.News.picupload_uploaddir}/pic_sid{$item.article.sid}-0-norm.jpg" >
            {/if}
        {/foreach}
    </div>
      {if $count > 0}
    <div class="all-events">
        <a href="{modurl modname='ZSELEX' type='user' func='showEvents' shop_id=$smarty.request.shop_id}" class="view-all">{gt text='view all'}</a>
    </div>
     {/if}
</div>