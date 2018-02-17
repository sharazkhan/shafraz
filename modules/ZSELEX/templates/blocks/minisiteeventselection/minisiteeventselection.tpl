<input type="hidden" id="eventlimit" value="{$vars.eventlimit}">
<input type="hidden" id="eventdate" value="">
<input type="hidden" id="morelimit" value="5">
<style>

#eventblock h3 {
    margin: 0;
}
</style>
<div id="eventblock"  style="width:400px;  height:auto; display:table-cell;">
    {*
      {foreach from=$events item='event'}
    <div style='border:solid 1px #CCC; padding-left:15px; padding-top:15px; padding-bottom:5px'> 

    <div>
        <b>Event Name</b>: 
        {$event.shop_event_name}
    </div>

    <div><b>Event Description</b>: {$event.shop_event_description}</div>

    <div><b>Start Date</b>: {$event.shop_event_startdate}</div>

    <div><b>Start Time</b>: Hr -{$event.shop_event_starthour}  Min - {$event.shop_event_startminute}  </div>

    <div><b>End Date</b>: {$event.shop_event_enddate}</div>

    <div><b>End Time</b>: Hr -{$event.shop_event_endhour}  Min - {$event.shop_event_endminute}  </div>
    <div align="right">
        <a href="{modurl modname='ZSELEX' type='user' func='viewevent' shop_id=$smarty.request.shop_id eventId=$event.shop_event_id}"><font color="blue">view detail...</font></a>
    </div>
    </div>
        {/foreach}
        *}
        <dt> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {gt text='No Events Found.'} </dt>
</div>

