

 {if $pictures gt 0}
        {setimagesize sid=$sid picture=$pictures imagepath=$picupload_uploaddir}
   {if $readperm}
         <a href="{modurl modname='News' type='user' func='display' sid=$sid}">    
    {/if}

   <img src="{$baseurl}{$picupload_uploaddir}/pic_sid{$sid}-0-norm.jpg" {if $width neq ''} width={$width} height={$height} {else} height="88" width="200" {/if}>

   {if $readperm}
   </a>
   {/if}
{/if}

{* {if $itemnewimage}{img modname='core' set=$newimageset src=$newimagesrc __alt='New'}{/if} *}

<p class="sec1H">{$title|safehtml}{if $titlewrapped}{$titlewraptxt|safehtml}{/if}</p>

{* 
{if $dispinfo}({if $dispuname}{gt text='by %s' tag1=$uname|profilelinkbyuname}
{if $dispdate} {gt text='on %s' tag1=$from|dateformat:$dateformat} {elseif $dispreads OR $dispcomments}{$dispsplitchar} {/if}{/if}
{if $dispreads}{if $counter gt 0}{gt text='%s pageview' plural='%s pageviews' count=$counter tag1=$counter}{/if}{if $dispcomments}{$dispsplitchar} {/if}{/if}
{if $dispcomments and $comments gt 0}{gt text='%s comment' plural='%s comments' count=$comments tag1=$comments}{/if})
{/if}

*}

{if $disphometext}
<span class="sec1T">
    {if $hometextwrapped}
    {$hometext|notifyfilters:'news.hook.articlesfilter.ui.filter'|truncatehtml:$maxhometextlength:''|safehtml}
    {if $readperm}<a href="{modurl modname='News' type='user' func='display' sid=$sid}">{/if}
    {$hometextwraptxt|safehtml}
    {if $readperm}</a>{/if}
{else}
    {$hometext|notifyfilters:'news.hook.articlesfilter.ui.filter'|safehtml}
{/if}
</span>
{/if}

<p class="sec1L">
    {if $smarty.request.func eq 'shop'}
      <a href="{modurl modname='ZSELEX' type='user' func='display' shop_id=$smarty.request.shop_id sid=$sid}">{gt text="Read this full article"}...<img src="{$imagepath}/Arrow1.png" /></a>
    {elseif $smarty.request.module eq 'ZSELEX' AND $smarty.request.func eq 'display'}
      <a href="{modurl modname='ZSELEX' type='user' func='display' shop_id=$smarty.request.shop_id sid=$sid}">{gt text="Read this full article"}...<img src="{$imagepath}/Arrow1.png" /></a>
    {else}  
      <a href="{pnmodurl modname='News' func='display' sid=$sid}">{gt text='Read this full article'}...<img src="{$imagepath}/Arrow1.png" style="margin-left: 8px" /></a>
    {/if}
    </p>






{* Remove this line to use the topic link and topicimage per News item -->
{if $topicsearchurl neq ''}
<div class="storiesext_news_meta"><a href="{$topicsearchurl}">{if $topicimage neq ''}<img src="{$catimagepath}{$topicimage}" alt="{$topicname|safehtml}" title="{$topicname|safehtml}" />{else}{$topicname|safehtml}{/if}</a></div>
{/if}
<!-- Remove this line to use the topic link and topicimage per News item *}
