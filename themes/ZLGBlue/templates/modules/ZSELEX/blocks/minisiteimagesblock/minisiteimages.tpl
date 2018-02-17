{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}


{if $perm}
    {if $servicePerm gt 0}
<table class="edit" style="padding-top:1px;padding-left:20px">
    <tr>
        <td>
    {if $vars.displayinfo eq 'yes'}
      <a class="infoclass"  id="miniSiteImageInfo" href="{modurl modname='ZSELEX' type='info' func='displayInfo' bid=$bid}" title="{gt text=$info.title}">
          <img  src="{$baseurl}images/icons/extrasmall/info.png">
      </a>
    {/if}
       </td>
       <td>&nbsp;</td>
        <td>
    <a href="{modurl  modname='ZSELEX' type='admin' func='viewshopimages' shopId=$smarty.request.shopId}">
      <img title="{gt text=Manage}"  src="{$baseurl}images/icons/extrasmall/configure.png">
      </a>
        </td>
   </tr>
</table>
          {/if}
      {/if}
<!--div style="clear:both"></div-->
<div style='border:solid 1px #CCC; padding-left:10px;padding-right:10px; padding-top:10px; padding-bottom:10px'> 
{foreach item='item' key=index from=$images}
     <span>
      <a id="my{$item.fileId}" rel="imageviewer[galleryService]" title="{$item.filedescription}" href="{$baseurl}modules/ZSELEX/images/shops/{$item.name}">
             <img src="{$baseurl}modules/ZSELEX/images/shops/thumbs/{$item.name}">
      </a>
      </span>
 {/foreach}
 </div>
 
 <script type="text/javascript">
 var defwindowajax = new Zikula.UI.Window($('miniSiteImageInfo'),{resizable: true });
  var $curr = jQuery(".edit");
      $curr = $curr.prev();
      $curr.css("width", "auto");
      $curr.css("float", "left");
      $curr.css("padding-top", "2px");
      $curr.css("background-position", "1px 2px");
 </script>
 
 