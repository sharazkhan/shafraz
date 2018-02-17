

{assign var=limit value=3}
{switch expr=$icon_size}
    {case expr='large'}
    {assign var=limit value='3'}
    {/case}
    {case expr='medium'}
    {assign var=limit value='4'}
    {/case}
    {case expr='small'}
    {assign var=limit value='6'}
    {/case}
{/switch}
{assign var=counter value=1}
<div class="shop-social col-md-12 col-sm-5 col-md-pull-0 col-sm-pull-6 col-xs-5 col-xs-pull-6">

    <ul>
        {*
        <li><a href="#"><img src="{$themepath}/images/facebook-icon.jpg" alt=""></a></li>
        <li><a href="#"><img src="{$themepath}/images/linked-icon.jpg" alt=""></a></li>
        <li><a href="#"><img src="{$themepath}/images/twitter-icon.jpg" alt=""></a></li>
        <li><a href="#"><img src="{$themepath}/images/pin-icon.jpg" alt=""></a></li>
        <li><a href="#"><img src="{$themepath}/images/gplus-icon.jpg" alt=""></a></li>
        <li><a href="#"><img src="{$themepath}/images/youtube-icon.jpg" alt=""></a></li>
        *}
        {foreach from=$social_links item='item'}
            {if $item.status eq true}
                {if $item.link_url neq ''}
                    <li>
                        <a href="{$item.link_url}" target="_blank">
                            <img src="{$themepath}/images/social_icons/{$icon_size}/{$item.socl_image}" title="{$item.socl_link_name}" alt="{$item.socl_link_name}">
                        </a>
                    </li>

                    {assign var=counter value=$counter+1}
                    {if $counter > $limit}
                        {assign var=counter value=1}
                        
                    {/if}  
                {/if}    
            {/if}    
        {/foreach}
    </ul>
</div>