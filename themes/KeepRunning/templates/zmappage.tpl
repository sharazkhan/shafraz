
{if $smarty.request.print eq 'map'}
 {include file="printmap.tpl"}
 {else}
{include file="zmap.tpl"}
 {/if}