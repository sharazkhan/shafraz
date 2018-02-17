{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}

 {if $perm}
       {if $servicePerm gt 0}
<table class="edits">
    <tr>
        <td>
    {if $vars.displayinfo eq 'yes'}
      <a class="infoclass"  id="galleryInfo" href="{modurl modname='ZSELEX' type='info' func='displayInfo' servicetype='minisitegallery' bid=$bid}" title="{$info.title}">
          <img  src="{$baseurl}images/icons/extrasmall/info.png">
      </a>
    {/if}
       </td>
       <td>&nbsp;</td>
        <td>
    <a href="{modurl  modname='ZSELEX' type='admin' func='viewshopgalleryimages' shop_id=$smarty.request.shop_id}">
        <img title="{gt text=Manage}"  src="{$baseurl}images/icons/extrasmall/configure.png">
    </a>
        </td>
   </tr>
</table>
     {/if}
    {/if}
 
<link rel="stylesheet" type="text/css" href="{$stylepath}/minisitegallery.css"/>
<div id="clearboth"></div>
<div> 
{foreach item='item' key=index from=$images}
     <span {if $item.defaultImg eq '1'} style="display:block" {else} style="display:none" {/if}>
      <a id="my{$item.gallery_id}" rel="imageviewer[galleryService1]" title="{$item.image_description}" href="{$baseurl}zselexdata/{$ownerName}/minisitegallery/{$item.image_name}">
             <img src="{$baseurl}zselexdata/{$ownerName}/minisitegallery/thumb/{$item.image_name}">
      </a>
      </span>
 {/foreach}
 </div>
 
 <inputt type="hidden" id="testw" value="hello">
 <script type="text/javascript">
 var defwindowajax = new Zikula.UI.Window($('galleryInfo'),{resizable: true });
 </script>
 {jquerycss}