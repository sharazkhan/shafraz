
{*
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
*}



<input type="hidden" id="upcommingeventlimit" value="{$vars.upcommingeventlimit}">

<div class="col-md-4 contents-right">
  <h2>{gt text="Events"}</h2>
<div id="upcommingevents">
      {include file="ajax/upcommingevents.tpl"}
</div>
  </div>

