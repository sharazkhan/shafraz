{ajaxheader imageviewer="true"}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/minisiteimages.css"}
<style>
    #myimages{
        text-align:center; 
        height: 98px; 
        width: 98px; 
        border:solid 1px black; 
        
    }
</style>

     
 {*<div id="minisiteimage_block">
 <h3 class="Skinh3">Arrangementer:</h3>
                       <div class="bodyImage">
                            {foreach item='item' key=index from=$images}
                                 <div class="ImageThumbSec"  style="background: url({$baseurl}zselexdata/{$ownerName}/minisiteimages/medium/{$item.name|replace:' ':'%20'}) no-repeat center center;{if $index+1 > 2}display:none{/if}">
                                 <a id="my{$item.file_id}" rel="imageviewer[minisiteimageGallery]" title="{$item.filedescription}" href="{$baseurl}zselexdata/{$ownerName}/minisiteimages/fullsize/{$item.name}" style="width:238px; height:195px; display: block;">
                                 {if $index+1 eq 2 && $count > 2}<div style="padding-left:10px;margin-top: 165px; font-size:16px;">{gt text='More images'}...</div>{/if} 
                                 </a>
                                 
                                 </div>
                                 
                               {/foreach}
                          </div>
    </div>*}
                          
    {if $perm}
     <div class="OrageEditSec EditImages">
         <a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}">
             <img src="themes/CityPilot/images/OrageEdit.png">{gt text='Edit Pictures'}
         </a>
     </div>
 {/if}                         
 <h3 class="Skinh3">{gt text='Photos'}:</h3>
 <div id="minisiteimage_block">
<ul class="RightImagePreview"> 
   {foreach item='item' key=index from=$images}
       {assign var="image1" value=$item.name|replace:' ':'%20'}
       {assign var="image" value="zselexdata/`$ownerName`/minisiteimages/thumb/`$image1`"}
           {if is_file($image)}
     <li class="RightImageBlock" style="background: url({$baseurl}zselexdata/{$ownerName}/minisiteimages/thumb/{$item.name|replace:' ':'%20'}) no-repeat center center;{if $index+1 > 4}display:none{/if}">
             {*<img src="{$baseurl}zselexdata/{$ownerName}/minisitegallery/thumb/{$item.image_name}"  /> *}
             <a style="width:104px; height:94px;cursor:pointer;display:block" id="my{$item.file_id}" rel="imageviewer[minisiteimages]" title="{$item.filedescription}" href="{$baseurl}zselexdata/{$ownerName}/minisiteimages/fullsize/{$item.name}">
             </a>
    </li>
            {/if}
   
     {/foreach}
</ul>
     </div>
                                

 