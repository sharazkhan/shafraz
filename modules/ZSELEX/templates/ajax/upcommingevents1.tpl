<div class="DateBlock">
      {if $event.aff_id > 0}
                          
                           {assign var="imagename" value=$event.aff_image|replace:' ':'%20'}
                             {assign var="image" value="modules/ZSELEX/images/affiliates/`$imagename`"}
                              {if is_file($image)}
                                {*<div style="width:50px; height:50px; position:relative; z-index:999; background: url(modules/ZSELEX/images/affiliates/{$imagename}) no-repeat center; display:inline-block; float:right; margin-top:-45px; margin-right: 30px;">
                                </div>*}
                                 <div class="UcommmingEvntAff" style="">
                                    <img src="modules/ZSELEX/images/affiliates/{$imagename}" >
                                 </div>
                              {/if}
                          
                       {/if}   
    <div style="z-index: 999; position: relative"><a class="HoverEffet" href="{$baseurl}{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$event.shop_id eventId=$event.shop_event_id}">
        <div class="DateBorder">
            <div class="Date">
                <span class="DateSpan">{$day}/{$month}</span><br><span class="YearSpan">{$year}</span><br><span class="WeekDay">{$dayname}</span>
            </div>
            <div class="DateHead">

                <div style="min-width:100%" class="EventName">
                    <h5>{$event.shop_event_name|cleantext|wordwrap:19:"<br/>":true}</h5>
                    
                </div>
                <div style="width:70%; padding-bottom:5px;" class="left">
                    <h6>{$event.shop_event_shortdescription|cleantext}</h6>
                </div>
                <div style="width:30%; text-align:right" class="left">
                    <div class="DatePrice"><p>{gt text='Price'} {if $event.price > 0}{displayprice amount=$price}{else}{gt text='FREE'}{/if}</p></div>
                </div>

            </div>

        </div>
    </a></div>
  
</div>