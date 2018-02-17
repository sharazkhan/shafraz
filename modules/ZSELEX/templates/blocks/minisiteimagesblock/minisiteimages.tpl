
{*{ajaxheader imageviewer="true"}*}

{if $perm}
<a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}#aImages" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>
    {gt text='Edit Pictures'}
</a>
{/if}

{if $count > 0}
<div class="col-sm-12">
<ul class="image-lightbox clearfix">
     {foreach item='item' key=index from=$images}
          {assign var="image1" value=$item.name|replace:' ':'%20'}
          {assign var="image" value="zselexdata/`$shop_id`/minisiteimages/thumb/`$image1`"}
           {if is_file($image)}
                {imageproportional image=$item.name path="`$baseurl`zselexdata/`$shop_id`/minisiteimages/thumb" height="100" width="150"}
    <li style="{if $index+1 > 4}display:none{/if}">
         <a class="fancybox" id="my{$item.file_id}" rel="imageviewer[minisiteimageGallery]" title="{$item.filedescription}" href="{$baseurl}zselexdata/{$shop_id}/minisiteimages/fullsize/{$item.name}" >
            <img {*{$imagedimensions}*} src="{$baseurl}zselexdata/{$shop_id}/minisiteimages/thumb/{$item.name|replace:' ':'%20'}" alt="" class="img-responsive"/>
            {if $index+1 eq 4 && $count > 4} <span>{gt text='more images'}...</span>{/if}
         </a>
         
    </li>
    {/if}
   
    {/foreach}
</ul>
</div>
{/if}