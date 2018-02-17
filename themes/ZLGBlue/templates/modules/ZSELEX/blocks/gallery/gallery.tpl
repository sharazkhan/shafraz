{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}

 {if $perm}
       {if $servicePerm gt 0}
<table class="edit" style="padding-top:1px;padding-left:20px">
    <tr>
        <td>
    {if $vars.displayinfo eq 'yes'}
      <a class="infoclass"  id="galleryInfo" href="{modurl modname='ZSELEX' type='info' func='displayInfo' bid=$bid}" title="{$info.title}">
          <img  src="{$baseurl}images/icons/extrasmall/info.png">
      </a>
    {/if}
       </td>
       <td>&nbsp;</td>
        <td>
    <a href="{modurl  modname='ZSELEX' type='admin' func='viewshopgalleryimages' shopId=$smarty.request.shopId}">
        <img title="{gt text=Manage}"  src="{$baseurl}images/icons/extrasmall/configure.png">
    </a>
        </td>
   </tr>
</table>
     {/if}
    {/if}
 
    
<!--div style="clear:both"></div-->
<div> 
{foreach item='item' key=index from=$images}
     <span {if $item.defaultImg eq '1'} style="display:block" {else} style="display:none" {/if}>
      <a id="my{$item.galleryId}" rel="imageviewer[galleryService1]" title="{$item.imageDescription}" href="{$baseurl}modules/ZSELEX/images/shopgallery/{$item.imageName}">
             <img src="{$baseurl}modules/ZSELEX/images/shopgallery/thumbs/{$item.imageName}">
      </a>
      </span>
 {/foreach}
 </div>
 
 <inputt type="hidden" id="testw" value="hello">
 <script type="text/javascript">
 var defwindowajax = new Zikula.UI.Window($('galleryInfo'),{resizable: true });
 
  //var $currs = jQuery('#testw').val();
  //alert($currs);
  // var $curr1 = jQuery(".edit");
    //$curr1.css("float", "left");
  var $curr = jQuery(".edit");
      $curr = $curr.prev();
      $curr.css("width", "auto");
      $curr.css("float", "left");
      $curr.css("padding-top", "2px");
      $curr.css("background-position", "1px 2px");
 </script>
 