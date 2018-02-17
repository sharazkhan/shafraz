
<ul>
    {if isset($users) and is_array($users) and count($users) gt 0}
 {foreach from=$users  item='usr'}
     <li id="{$usr.customer_id}">{$usr.first_name|safetext}</li>
 {/foreach}
    {/if}
 </ul>