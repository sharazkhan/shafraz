 

    <div class="DateBlock" style="display:block">
        {counter assign=idx start=0 print=0}
        {foreach from=$events.dates item='event' key='date'}
            {assign var='datebreak' value='-'|explode:$date}
            {foreach from=$events.dates.$date item='eventx'}
                {counter}
                {if $idx <= $limit}
                <a href="{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$eventx.shop_id eventId=$eventx.shop_event_id}" class="HoverEffet">
                    <div class="DateBorder">
                        <div class="Date">
                            <span class="DateSpan">{$datebreak[2]}/{$datebreak[1]}</span><br /><span  class="YearSpan">{$datebreak[0]}</span><br /><span  class="WeekDay">{dayname date=$date}</span>
                        </div>
                        <div class="DateHead">
                            <h5>{$eventx.shop_event_name|cleantext}</h5>
                            {* <div style="width:50px; height:50px; position:relative; z-index:999; background: url(themes/CityPilot/images/eventlevel.png) no-repeat center; display:inline-block; float:right; margin-top:-45px; margin-right: 30px;">
                             </div> *}
                             
                            {if $eventx.aff_id > 0}
                             {assign var="imagename" value=$eventx.aff_image|replace:' ':'%20'}
                             {assign var="image" value="modules/ZSELEX/images/affiliates/`$imagename`"}
                              {if is_file($image)}
                                {*<div style="width:50px; height:50px; position:relative; z-index:999; background: url(modules/ZSELEX/images/affiliates/{$imagename}) no-repeat center; display:inline-block; float:right; margin-top:-45px; margin-right: 30px;">
                                </div>*}
                                 <div style="width: 50px; height: 50px; position: relative;z-index:999;display:inline-block; float:right; margin-top:-45px; margin-right: 30px;">
                                    <img src="modules/ZSELEX/images/affiliates/{$imagename}" style="width: 50px;">
                                 </div>
                              {/if}
                            {/if}
                            <h6>{$eventx.shop_event_shortdescription|cleantext}</h6>
                        </div>
                        <div class="DatePrice">
                            <p>{gt text='Price'} : {if $eventx.price > 0}{displayprice amount=$eventx.price}{else}{gt text='FREE'}{/if}</p>
                            {gt text='Time'} : {$eventx.shop_event_startdate}{if $eventx.shop_event_starthour > 0}, {$eventx.shop_event_starthour}{/if} - {if ($eventx.shop_event_enddate != $eventx.shop_event_startdate)}{$eventx.shop_event_enddate}{if $eventx.shop_event_endhour > 0}, {/if}{/if}{if $eventx.shop_event_endhour > 0}{$eventx.shop_event_endhour}{/if}<br>
                            {gt text='Location'} : {$eventx.shop_event_venue|cleantext}
                           
                        </div>
                        <div class="Sect3">
                        {if !empty($eventx.phone)}{gt text='Contact'} : {$eventx.phone}<br>{/if}{gt text='Organizer'} : {$eventx.uname}<br>
                            <span class="Sect3Link">{gt text='See More...'}</span>
                        </div>
                    </div>
                </a>
                {/if}   
            {/foreach} 
         {foreachelse}
          {gt text='No Events Found'}
        {/foreach} 
        {if $count > $limit}
        <div class="load_more_link" align="right">
            <input type="button" class="eventmorebutton" value="{gt text='Load More Events'}" 
              onclick='showEvents(20);'>
            <span class="flash"></span>
        </div>
        {/if}
    </div>
    
   


  