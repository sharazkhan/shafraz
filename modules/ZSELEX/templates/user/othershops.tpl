<h4>Other Shops</h4>

<ul>
  
     {foreach from=$shops item='item'}
          
        <li>
            {if $modvars.ZConfig.shorturls eq 1}
            <a href={$baseurl}shop/{$item.urltitle}>{$item.shop_name}</a>
            {else}
            <a href={$baseurl}shop/{$item.urltitle}>{$item.shop_name}</a>
            {/if}
        </li>
    
    {/foreach}
</ul>