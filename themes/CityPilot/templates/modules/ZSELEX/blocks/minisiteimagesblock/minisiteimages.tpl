{ajaxheader imageviewer="true"}
{fileversion}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/minisiteimages.css$ver"}
<style>
    #myimages{
        text-align:center; 
        height: 98px; 
        width: 98px; 
        border:solid 1px black; 
        
    }
</style>
{if $perm}
    {if $servicePerm gt 0}
        {*
<table class="edits">
    <tr>
        <td>
    {if $vars.displayinfo eq 'yes'}
      <a class="infoclass"  id="miniSiteImageInfo" href="{modurl modname='ZSELEX' type='info' func='displayInfo' servicetype='minisiteimages' bid=$bid}" title="{gt text=$info.title}">
          <img  src="{$baseurl}images/icons/extrasmall/info.png">
      </a>
    {/if}
       </td>
       <td>&nbsp;</td>
        <td>
    <a href="{modurl  modname='ZSELEX' type='admin' func='viewshopimages' shop_id=$smarty.request.shop_id}">
      <img title="{gt text=Manage}"  src="{$baseurl}images/icons/extrasmall/configure.png">
      </a>
        </td>
   </tr>
</table>
*}
          {/if}
      {/if}
      
      {*
<link rel="stylesheet" type="text/css" href="{$stylepath}/minisiteimages.css"/>
<div id="clearboth"></div>
<div id="minisiteimage_block"> 
{foreach item='item' key=index from=$images}
     <span>
      <a id="my{$item.file_id}" rel="imageviewer[galleryService]" title="{$item.filedescription}" href="{$baseurl}zselexdata/{$ownerName}/minisiteimages/fullsize/{$item.name}">
             <img src="{$baseurl}zselexdata/{$ownerName}/minisiteimages/thumb/{$item.name}">
      </a>
      </span>
 {/foreach}
 </div>
 
 {jquerycss}
 *}

 {if $perm}
     <div class="OrageEditSec">
         <a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}#aImages">
             <img src="{$themepath}/images/OrageEdit.png">{gt text='Edit Pictures'}
         </a>
     </div>
 {/if}    

{if $count > 0}
 <div id="minisiteimage_block">
 <ul class="ImagePrivew">
   
    {foreach item='item' key=index from=$images}
          
         
          {assign var="image1" value=$item.name|replace:' ':'%20'}
          {assign var="image" value="zselexdata/`$shop_id`/minisiteimages/thumb/`$image1`"}
        
           {if is_file($image)}
           <li style="background: url({$baseurl}zselexdata/{$shop_id}/minisiteimages/thumb/{$item.name|replace:' ':'%20'}) no-repeat center center;{if $index+1 > 5}display:none{/if}" >
                {imageproportional image=$item.name path="`$baseurl`zselexdata/`$shop_id`/minisiteimages/thumb" height="98" width="98"}
                <a id="my{$item.file_id}" rel="imageviewer[minisiteimageGallery]" title="{$item.filedescription}" href="{$baseurl}zselexdata/{$shop_id}/minisiteimages/fullsize/{$item.name}" style="width:98px; height:98px; display: block;">
              
               {if $index+1 eq 5 && $count > 5} <span style="top:104px; display: block; position: relative;left:23px">{gt text='more images'}...</span>{/if}
                </a>
                 {*<a id="my{$item.file_id}" rel="imageviewer[minisiteimageGallery]" title="{$item.filedescription}" href="{$baseurl}zselexdata/{$ownerName}/minisiteimages/fullsize/{$item.name}" style="display: block; margin-top: 6px; width: 98px; text-align: right">
                 {if $index+1 eq 5 && $count > 5}<span style="">{gt text='more images'}...</span>{/if}
                 </a>*}
           </li>
           {/if}
          
            
    {/foreach}
 </ul>
 </div>
{/if}
