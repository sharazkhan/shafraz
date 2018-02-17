{if $totalcount > 0}
 {counter assign=idx start=0 print=0}
    {foreach from=$events item='event' key='date'}  
    {assign var='datebreak' value='-'|explode:$date}
    {foreach from=$events.$date item='eventx' key=index}
     {counter}
     {if $idx <= $eventlimit}
        
    <div class="DateBlock">
        {if $eventx.aff_id > 0}

        {assign var="imagename" value=$eventx.aff_image|replace:' ':'%20'}
        {assign var="image" value="modules/ZSELEX/images/affiliates/`$imagename`"}
        {if is_file($image)}
        {*<div style="width:50px; height:50px; position:relative; z-index:999; background: url(modules/ZSELEX/images/affiliates/{$imagename}) no-repeat center; display:inline-block; float:right; margin-top:-45px; margin-right: 30px;">
        </div>*}
        <div class="UcommmingEvntAff" style="">
            <img src="{$baseurl}modules/ZSELEX/images/affiliates/{$imagename}" >
        </div>
        {/if}

        {/if}   

        <div style="z-index: 999; position: relative">
            <a href="{modurl modname="ZSELEX" type="user" func="viewevent" shop_id=$eventx.shop_id eventId=$eventx.shop_event_id}" class="HoverEffet">
               <div class="DateBorder">
                    <div class="Date">
                        <span class="DateSpan">{$datebreak[2]}/{$datebreak[1]}</span><br><span class="YearSpan">{$datebreak[0]}</span><br><span class="WeekDay">{dayname date=$date}</span>
                    </div>
                    <div class="DateHead">

                        <div class="EventName" style="min-width:100%">
                            <h5>{$eventx.shop_event_name|cleantext|wordwrap:19:"<br/>":true}</h5>

                        </div>
                        <div class="left" style="width:70%; padding-bottom:5px;">
                            <h6>{$eventx.shop_event_shortdescription|cleantext}</h6>
                        </div>
                        <div class="left" style="width:30%; text-align:right">
                            <div class="DatePrice"><p>{gt text='Price'} {if $eventx.price > 0}{displayprice amount=$eventx.price}{else}{gt text='FREE'}{/if}</p></div>
                        </div>

                    </div>

                </div>
            </a>
        </div>

    </div>
                        {/if}
    
    {/foreach}  
   
    {/foreach} 
    {else}
          &nbsp;&nbsp;&nbsp;&nbsp; {gt text='No events found'} 
        {/if}
    
     {if $totalcount > $eventlimit}
        <a href="{modurl modname="ZSELEX" type="user" func="showEvents"}">{gt text='All Events'}</a>
     {/if}
