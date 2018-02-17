
{if $events }
<ul class="events-list clearfix">
    {foreach from=$events item='event' key='date'}
    {assign var='datebreak' value='-'|explode:$event.event_date}
    <li>

        <a {if $event.event_link!='' && $event.call_link_directly eq 1}{if $event.open_new}target="_blank"{/if}{/if} href="{if $event.event_link!='' && $event.call_link_directly eq 1}{$event.event_link}{else}{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$event.shop_id eventId=$event.shop_event_id}{/if}" class="clearfix">
            <div class="event-date">
                <span class="ev-date">{$datebreak[2]}/{$datebreak[1]}</span>
                <span class="ev-year">{$datebreak[0]}</span>
                <span class="ev-weekday">{dayname date=$event.event_date}</span>
            </div>
            <div class="event-title">
                <h3>{$event.shop_event_name|cleantext|wordwrap:19:"<br/>":true}</h3>
                {if $event.aff_id > 0}
                {assign var="imagename" value=$event.aff_image|replace:' ':'%20'}
                {assign var="image" value="modules/ZSELEX/images/affiliates/`$imagename`"}
                {if is_file($image)}
                <span class="e-icon"><img src="{$baseurl}/modules/ZSELEX/images/affiliates/{$imagename}" alt="" width="49" height="50"></span>
                {/if}
                {/if}
            </div>
            <div class="event-contents">
                <div class="clearfix">
                    <p>{gt text='Price'}  : {if $event.price > 0}{displayprice amount=$event.price}{else}{gt text='FREE'}{/if} <br>
                        {gt text='Time'} : {$event.shop_event_startdate}{if $event.shop_event_starthour > 0}, {$event.shop_event_starthour}{/if} - {if ($eventx.shop_event_enddate != $eventx.shop_event_startdate)}{$eventx.shop_event_enddate}{if $eventx.shop_event_endhour > 0}, {/if}{/if}{if $eventx.shop_event_endhour > 0}{$eventx.shop_event_endhour}{/if} <br>
                        {gt text='Location'} : {$event.shop_event_venue|cleantext}
                    </p>
                </div>
            </div>
            <div class="seemore">
                <p> 
                    {if !empty($event.phone)}{gt text='Contact'} : {$event.phone}<br>{/if}
                    {gt text='Organizer'} : {if !empty($event.contact_name)}{$event.contact_name}{else}{$event.shop_name}{/if} <br>
                    <span class="see-more">See More...</span>
                </p>
            </div>
        </a>
    </li>
    {foreachelse}
    <li> {gt text='No Events Found'}</li>
    {/foreach} 

</ul>
    {else}
        <div>&nbsp;&nbsp;&nbsp;&nbsp; {gt text='No Events Found'}</div>
    {/if}
      {if $count > 0}
<div align="right" class="load_more_link">
    <a href="#" class="load-btn btn btn-primary" onclick='return showEvents({$limit});'><i class="fa fa-refresh"></i>Load More Events</a>
    <span class="flash"></span>
</div>
      {/if}
