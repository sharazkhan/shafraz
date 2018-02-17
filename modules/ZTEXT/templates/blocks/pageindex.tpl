{if $perm}
<div class="OrageEditSec EditEvent" style="">
    <a href="{modurl modname="ZTEXT" type="admin" func="pages" shop_id=$smarty.request.shop_id}">
        <img src="{$themepath}/images/OrageEdit.png">{gt text='Edit Pages'}
    </a>
</div>
    {/if}
    {if !$disable_page_index}
<h3 class="EventName">{gt text='Page Index'}:</h3>
<div style="font-size: 14px">
{foreach from=$pages item='page'}
    <p>
        &nbsp;&nbsp;<a {if $smarty.request.text_id eq $page.text_id}style="color: #e65622;"{/if} href="{modurl modname="ZTEXT" type="user" func="page" shop_id=$smarty.request.shop_id text_id=$page.text_id}">{$page.headertext|cleantext}</a>
    </p>
{/foreach}
</div>
{/if}