{*
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
*}



<input type="hidden" id="upcommingeventlimit" value="{$vars.upcommingeventlimit}">


<style>

    #eventblock h3 {
        margin: 0;
    }

</style>
<div id="upcommingevents">
      {include file="ajax/upcommingevents.tpl"}
</div>

