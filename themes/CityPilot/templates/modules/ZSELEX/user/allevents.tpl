 
<input type="hidden" id="upcommingeventlimit" value="{$vars.upcommingeventlimit}">
<input type="hidden" id="shop_id" value="{$smarty.request.shop_id}">
<div class="z-admin-content-pagetitle">
    <h4>{gt text='Events'}</h4>
</div>

<div id="pageData" class="showevents Allevents" style="display:inline-block">
      {include file="ajax/show_all_events.tpl"}
   
</div> <!-- main -->

    <div class="z-buttons z-formbuttons" style="display:block">
    	{if $smarty.request.shop_id}
	        <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" title="{gt text="Back"}">
        {else}
	        <a href="{homepage}" title="{gt text="Back"}">
        {/if}
        		{img modname='ZSELEX' src="icon_cp_backtoshoplist.png" __alt="Back" __title="Back"} {gt text="Back"}
            </a>
    </div>
