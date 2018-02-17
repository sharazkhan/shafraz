 {ajaxheader imageviewer="true"}
<div class="ShopRatingDiv">
{foreach from=$pages item='item'}
 {if $perm}
<div class="OrageEditSec EditEvent">
    <a href="{modurl modname="ZTEXT" type="admin" func="pages" shop_id=$smarty.request.shop_id text_id=$item.text_id}">
        <img src="{$themepath}/images/OrageEdit.png">{gt text='Edit Page'}
    </a>
</div>
 {/if}
    <h3>{$item.headertext|cleantext}</h3>
     {$item.bodytext}
   
     {if $item.extension eq 'pdf'}
       {assign var="imageExist" value="zselexdata/`$smarty.request.shop_id`/ztext/pdf/medium/`$item.image`"}
        {if is_file($imageExist)}
        <p>
            <a target="_blank" href="{modurl modname="ZTEXT" type="user" func="pdfView" id=$item.text_id}">
        <img  src="{$baseurl}zselexdata/{$smarty.request.shop_id}/ztext/pdf/medium/{$item.image}">
            </a>
        </p>
       {/if}
      {else}
        {assign var="imageExist" value="zselexdata/`$smarty.request.shop_id`/ztext/medium/`$item.image`"}
        {if is_file($imageExist)}
        <p>
             <a id="my{$item.text_id}" rel="imageviewer[pageGallery]" title="{$item.headertext}" href="{$baseurl}zselexdata/{$smarty.request.shop_id}/ztext/fullsize/{$item.image}">
        <img  src="{$baseurl}zselexdata/{$smarty.request.shop_id}/ztext/medium/{$item.image}">
             </a>
        </p>
       {/if}
     {/if}
     
       {assign var="imagePath" value="`$baseurl`zselexdata/`$smarty.request.shop_id`/ztext/medium/`$item.image`"}
       {modurl modname="ZTEXT" type="user" func="page" shop_id=$smarty.request.shop_id text_id=$item.text_id assign="url"}
       {assign var="pageUrl" value="`$baseurl``$url`"}
       
     <div>{fblikeservice action='like' url=$pageUrl width="500px" height="21px" layout='horizontal' shop_id=$smarty.request.shop_id addmetatags=true metatitle=$item.headertext metatype="website" metaimage=$imagePath description=$item.bodytext|strip_tags faces=true}</div>
     <div>{fbshare shop_id=$smarty.request.shop_id  url=$pageUrl}</div>


{/foreach}
</div>
<div align="center" style="float:center">
    {pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages=5}
</div>
{if $pages}<br>{/if}

 


