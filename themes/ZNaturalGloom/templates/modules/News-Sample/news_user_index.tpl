<div class="post">

    <div class="post_title"><h2>{$preformat.catandtitle}</h2></div>
    <div class="post_date">{gt text='Contributed'} {gt text='by %1$s on %2$s' tag1=$info.contributor tag2=$info.briefdatetime}</div>
    
    <div class="post_body">
        {$info.hometext}
    </div>
    
    {if $preformat.notes neq ''}
    <div class="post_meta">
        {$preformat.notes}
    </div>
    {/if}

    <div class="post_meta">
        {$preformat.readmore}
    </div>

</div>
