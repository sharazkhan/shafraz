 {ajaxheader imageviewer="true"}
<div class="ShopRatingDiv">
    {if $perm}
<div class="OrageEditSec EditEvent">
    <a href="{modurl modname="ZTEXT" type="admin" func="pages" shop_id=$smarty.request.shop_id text_id=$page.text_id}">
        <img src="{$themepath}/images/OrageEdit.png">{gt text='Edit Page'}
    </a>
</div>
 {/if}
    <h3>{$page.headertext|cleantext}</h3>
   {$page.bodytext}
    {if $page.extension eq 'pdf'}
         {assign var="imageExist" value="zselexdata/`$smarty.request.shop_id`/ztext/pdf/medium/`$page.image`"}
        {if is_file($imageExist)}
        <p>
             <a target="_blank" href="{modurl modname="ZTEXT" type="user" func="pdfView" id=$page.text_id}">
        <img src="{$baseurl}zselexdata/{$smarty.request.shop_id}/ztext/pdf/medium/{$page.image}">
             </a>
        </p>
       {/if}
    {else}    
        {assign var="imageExist" value="zselexdata/`$smarty.request.shop_id`/ztext/medium/`$page.image`"}
        {if is_file($imageExist)}
        <p>
             <a id="my{$page.text_id}" rel="imageviewer[pageGallery]" title="{$page.headertext}" href="{$baseurl}zselexdata/{$smarty.request.shop_id}/ztext/fullsize/{$page.image}">
        <img  src="{$baseurl}zselexdata/{$smarty.request.shop_id}/ztext/medium/{$page.image}">
             </a>
        </p>
       {/if}
    {/if}
   
</div>
   
     <div>{fblikeservice action='like' url=$url width="500px" height="21px" layout='horizontal' shop_id=$smarty.request.shop_id addmetatags=true metatitle=$page.headertext metatype="website" metaimage=$imagePath description=$page.bodytext|strip_tags faces=true}</div>
     <div>{fbshare shop_id=$smarty.request.shop_id url=$url}</div>
     <br>





