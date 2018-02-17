{if $totalcount > 0}
     <ul class="events-wrap clearfix">
     {foreach from=$events item='event' key='date'}  
           {assign var='datebreak' value='-'|explode:$event.event_date}
                  <!--  <ul class="events-wrap clearfix"> -->
                        <li>
                            {if $event.aff_id > 0}
                                 {assign var="imagename" value=$event.aff_image|replace:' ':'%20'}
        {assign var="image" value="modules/ZSELEX/images/affiliates/`$imagename`"}
          {if is_file($image)}
                            <span class="e-icon">
                               
                                  <img src="{$baseurl}modules/ZSELEX/images/affiliates/{$imagename}" alt="" width="49" height="50">
                            </span>
                           {/if}
           {/if}
                           
                             <a {if $event.event_link!='' && $event.call_link_directly eq 1}{if $event.open_new}target="_blank"{/if}{/if} href="{if $event.event_link!='' && $event.call_link_directly eq 1}{$event.event_link}{else}{modurl modname="ZSELEX" type="user" func="viewevent" shop_id=$event.shop_id eventId=$event.shop_event_id}{/if}" class="clearfix">
                                <div class="event-date">
                                    <span class="ev-date">{$datebreak[2]}/{$datebreak[1]}</span>
                                    <span class="ev-year">{$datebreak[0]}</span>
                                    <span class="ev-weekday">{dayname date=$event.event_date}</span>
                                </div>
                                <div class="event-contents">
                                    <h3>{$event.shop_event_name|cleantext|wordwrap:19:"<br/>":true}</h3>
                                    <div class="clearfix">
                                        <p class="event-contents-left">{shorttext text=$event.shop_event_shortdescription len=100}</p>
                                        <p class="event-contents-right">{gt text='Price'} {if $event.price > 0}{displayprice amount=$event.price}{else}{gt text='FREE'}{/if}</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                       

                   <!-- </ul> -->
                            {/foreach}
     </ul>
                             {else}
          &nbsp;&nbsp;&nbsp;&nbsp; {gt text='No events found'} 
        {/if}
        
         {if $totalcount > 10}
        <a href="{modurl modname="ZSELEX" type="user" func="showEvents"}">{gt text='All Events'}</a>
     {/if}
              