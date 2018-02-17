{gt text='My account' assign='templatetitle'}
{include file='users_user_menu.tpl'}

{foreach item='accountLink' from=$accountLinks}
<div class="z-accountlink" style="width:{math equation='100/x' x=$modvars.Users.accountitemsperrow format='%.0d'}%;">
     
     {if $modvars.Users.accountdisplaygraphics eq 1}
        {if isset($accountLink.set) && !empty($accountLink.set)}
            {assign var="iconset" value=$accountLink.set}
    {else}
            {assign var="iconset" value=null}
    {/if}
     {if $accountLink.url eq "news/newitem/" or $accountLink.url eq "index.php?module=news&type=user&func=newitem"}
      <a href="{modurl modname='ZSELEX' type='user' func='newitem'}">{img src=$accountLink.icon modname=$accountLink.module set=$iconset}</a>
        {else}
      <a href="{$accountLink.url|safetext}">{img src=$accountLink.icon modname=$accountLink.module set=$iconset}</a>
     {/if}
        <br />
    {/if}
   {if $accountLink.url eq "news/newitem/" or $accountLink.url eq "index.php?module=news&type=user&func=newitem"}
         <a href="{modurl modname='ZSELEX' type='user' func='newitem'}">{$accountLink.title|safetext}</a> 
     {else}
    <a href="{$accountLink.url|safetext}">{$accountLink.title|safetext}</a>
    {/if}
</div>
{/foreach}
<br style="clear: left" />
