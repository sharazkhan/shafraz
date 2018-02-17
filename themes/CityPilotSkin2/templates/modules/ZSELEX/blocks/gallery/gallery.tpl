{ajaxheader imageviewer="true"}

<link rel="stylesheet" type="text/css" href="{$stylepath}/minisitegallery.css"/>
<h3 class="Skinh3">Photos:</h3>

<ul class="RightImagePreview"> 
    {foreach item='item' key=index from=$images}
    {*<span>
        <a id="my{$item.gallery_id}" rel="imageviewer[galleryService1]" title="{$item.image_description}" href="{$baseurl}zselexdata/{$ownerName}/minisitegallery/{$item.image_name}">
            <img src="{$baseurl}zselexdata/{$ownerName}/minisitegallery/thumb/{$item.image_name}">
        </a>
    </span>*}
   
    <li class="RightImageBlock" style="background: url({$baseurl}zselexdata/{$ownerName}/minisitegallery/thumb/{$item.image_name|replace:' ':'%20'}) no-repeat center center;">
             {*<img src="{$baseurl}zselexdata/{$ownerName}/minisitegallery/thumb/{$item.image_name}"  /> *}
             <a style="width:104px; height:94px;cursor:pointer;display:block" id="my{$item.gallery_id}" rel="imageviewer[galleryService1]" title="{$item.image_description}" href="{$baseurl}zselexdata/{$ownerName}/minisitegallery/fullsize/{$item.image_name}">
             </a>
    </li>
   
     {/foreach}

</ul>

