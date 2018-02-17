<script>
jQuery(document).ready(function() {
    jQuery("img.lazy").lazyload();
});
</script> 

{if $eventcount < 1} 
        <style>
        
        #specialdeal_block_Events {
    background: none;
    border: 0px;
    clear: none;
    float: left;
    margin-top: 0px;
}
        </style>
        {/if} 
         {counter assign=idx_evnt start=0 print=0}
         {foreach item='event' from=$events}
              {counter}
                {ownerinfo shop_id=$event.shop_id}
        <div class="Sec1 {if $idx_evnt eq $eventcount} noborder{/if}">
            <div style="height:90px;">
            {if $event.showfrom eq 'image'} 
                    
        <a {if $event.event_link!='' && $event.call_link_directly eq 1}{if $event.open_new}target="_blank"{/if}{/if} href="{if $event.event_link!='' && $event.call_link_directly eq 1}{$event.event_link}{else}{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$event.shop_id eventId=$event.shop_event_id}{/if}">
            
            {assign var="image1" value=$event.event_image|replace:' ':'%20'}
            {assign var="image" value="zselexdata/`$event.shop_id`/events/medium/`$image1`"}
            {if file_exists($image)}
                  {imageproportional image=$event.event_image path="`$baseurl`zselexdata/`$event.shop_id`/events/thumb" height="90" width="128"}
             <img class="lazy"  {$imagedimensions}  src="{$baseurl}modules/ZSELEX/images/grey_small.gif"  data-original="{$baseurl}zselexdata/{$event.shop_id}/events/thumb/{$event.event_image|replace:' ':'%20'}" >
             {/if}
        </a>
         {elseif $event.showfrom eq 'product'}
        {if $event.shoptype eq 'iSHOP'}
        <a href="{modurl modname="ZSELEX" type='user' func='productview' id=$event.product_id}" target="_blank">
            {eventimage shoptype=$event.shoptype shop_id=$event.shop_id id=$event.product_id from=$event.showfrom}
            {imageproportional image=$eventimage path=$path height="90" width="128"}
           <img class="lazy" {$imagedimensions}  src="{$baseurl}modules/ZSELEX/images/grey_small.gif"  data-original="{$eventfullpath|replace:' ':'%20'}" >
        </a>
        {elseif $event.shoptype  eq 'zSHOP'}
            {eventimage shoptype=$event.shoptype shop_id=$event.shop_id id=$event.product_id from=$event.showfrom}
            {imageproportional image=$eventimage path=$path height="90" width="128"}
        <a href='http://{$zencart.domain}/index.php?main_page=product_info&products_id={$event.product_id}' target='_blank'>
             <img class="lazy" {$imagedimensions} src="{$baseurl}modules/ZSELEX/images/grey_small.gif"  data-original="{$eventfullpath|replace:' ':'%20'}" >
        </a>
        {/if}  
        
        {elseif $event.showfrom eq 'doc'} 
           {if $event.event_doc|pathinfo:$smarty.const.PATHINFO_EXTENSION eq 'pdf'}
                 {assign var="pdfimage" value=$event.event_doc|pathinfo:$smarty.const.PATHINFO_FILENAME}
                 {imageproportional image=$pdfimage|cat:".jpg" path="`$baseurl`zselexdata/`$event.shop_id`/events/docs/thumb" height="90" width="128"}
                 <a href="{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$event.shop_id eventId=$event.shop_event_id}">
                 <img class="lazy"  {$imagedimensions}  src="{$baseurl}modules/ZSELEX/images/grey_small.gif" data-original="zselexdata/{$event.shop_id}/events/docs/thumb/{$event.event_doc|pathinfo:$smarty.const.PATHINFO_FILENAME}.jpg" >
             </a>
           {elseif $event.event_doc|pathinfo:$smarty.const.PATHINFO_EXTENSION eq 'doc'} 
           {/if}   
        {/if}
            </div>
            <p class="sec1H">
                <a href="{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$event.shop_id eventId=$event.shop_event_id}">{shorttext text=$event.shop_event_name|cleantext len=40}</a>
            </p>
            <p class="sec1L">{if $event.price < 1}{gt text="FREE"}{else}{displayprice amount=$event.price}{/if}</p>
        </div>
        {/foreach}