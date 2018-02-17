
{counter assign=idx_evnt start=0 print=0}
{foreach item='event' from=$events}
<a {if $event.event_link!='' && $event.call_link_directly eq 1}{if $event.open_new}target="_blank"{/if}{/if} href="{if $event.event_link!='' && $event.call_link_directly eq 1}{$event.event_link}{else}{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$event.shop_id eventId=$event.shop_event_id}{/if}">
    <div class="col-sm-3 col-xs-6 special-sub-product hover-border nopadding">

        <div class="thumbnail">
            <div class="pro-image">
                {if $event.showfrom eq 'image'} 
                {assign var="image1" value=$event.event_image|replace:' ':'%20'}
                {assign var="image" value="zselexdata/`$event.shop_id`/events/medium/`$image1`"}
                {if file_exists($image)}
                {imageproportional image=$event.event_image path="`$baseurl`zselexdata/`$event.shop_id`/events/thumb" height="90" width="128"}
                <img class="lazy"  {$imagedimensions}   src="{$baseurl}zselexdata/{$event.shop_id}/events/thumb/{$event.event_image|replace:' ':'%20'}" >
                {/if}

                {elseif $event.showfrom eq 'product'}
                {if $event.shoptype eq 'iSHOP'}
                <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$event.product_id}" target="_blank">
                   {eventimage shoptype=$event.shoptype shop_id=$event.shop_id id=$event.product_id from=$event.showfrom}
                   {imageproportional image=$eventimage path=$path height="90" width="128"}
                   <img class="lazy" {$imagedimensions}   src="{$eventfullpath|replace:' ':'%20'}" >
                </a>
                {elseif $event.shoptype  eq 'zSHOP'}
                {eventimage shoptype=$event.shoptype shop_id=$event.shop_id id=$event.product_id from=$event.showfrom}
                {imageproportional image=$eventimage path=$path height="90" width="128"}
                <a href='http://{$zencart.domain}/index.php?main_page=product_info&products_id={$event.product_id}' target='_blank'>
                    <img class="lazy" {$imagedimensions}  src="{$eventfullpath|replace:' ':'%20'}" >
                </a>
                {/if}  

                {elseif $event.showfrom eq 'doc'} 
                {if $event.event_doc|pathinfo:$smarty.const.PATHINFO_EXTENSION eq 'pdf'}
                {assign var="pdfimage" value=$event.event_doc|pathinfo:$smarty.const.PATHINFO_FILENAME}
                {imageproportional image=$pdfimage|cat:".jpg" path="`$baseurl`zselexdata/`$event.shop_id`/events/docs/thumb" height="90" width="128"}

                <img class="lazy"  {$imagedimensions}  src="zselexdata/{$event.shop_id}/events/docs/thumb/{$event.event_doc|pathinfo:$smarty.const.PATHINFO_FILENAME}.jpg" >

                {elseif $event.event_doc|pathinfo:$smarty.const.PATHINFO_EXTENSION eq 'doc'} 
                {/if}   
                {/if}
            </div>
            <div class="product-caption">
                <h3>
                    {shorttext text=$event.shop_event_name|cleantext len=25}
                </h3>
                <div class="pro-sub-row clearfix">
                    <div class="product-amount">
                        {if $event.price < 1}{gt text="FREE"}{else}{displayprice amount=$event.price}{/if}
                    </div>
                </div>
            </div>
        </div>

    </div>
</a>
{/foreach}

