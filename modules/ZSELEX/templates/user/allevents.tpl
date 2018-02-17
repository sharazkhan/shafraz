
<input type="hidden" id="upcommingeventlimit" value="{$vars.upcommingeventlimit}">
<input type="hidden" id="shop_id" value="{$smarty.request.shop_id}">

<div class="row">
                <div class="col-md-12">
                    <h2>{gt text="Events"}</h2>
                    <div id="pageData">
                   {include file="ajax/show_all_events.tpl"}
                    </div>
                    <div class="event-foot clearfix">
                        	{if $smarty.request.shop_id}
                        <a href="{modurl modname="ZSELEX" type="user" func="site" shop_id=$smarty.request.shop_id}" class="back-btn btn btn-default"><i class="fa fa-arrow-circle-left"></i>{gt text="Back"}</a>
                        {else}
                              <a href="{homepage}" class="back-btn btn btn-default"><i class="fa fa-arrow-circle-left"></i>{gt text="Back"}</a>
                            {/if}
                        {*<a href="#" class="load-btn btn btn-primary"><i class="fa fa-refresh"></i>Load More Events</a>*}
                    </div>
                </div>
            </div>