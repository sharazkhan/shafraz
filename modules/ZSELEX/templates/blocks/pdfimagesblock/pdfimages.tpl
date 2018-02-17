{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}

{if $perm}
    {if $servicePerm gt 0}
<table class="edits">
    <tr>
        <td>
    {if $vars.displayinfo eq 'yes'}
      <a class="infoclass"  id="pdfInfo" href="{modurl modname='ZSELEX' type='info' func='displayInfo' servicetype='pdfupload' bid=$bid}" title="{gt text=$info.title}">
          <img  src="{$baseurl}images/icons/extrasmall/info.png">
      </a>
    {/if}
       </td>
       <td>&nbsp;</td>
        <td>
    <a href="{modurl  modname='ZSELEX' type='admin' func='viewshoppdf' shop_id=$smarty.request.shop_id}">
      <img title="{gt text=Manage}"  src="{$baseurl}images/icons/extrasmall/configure.png">
      </a>
        </td>
   </tr>
</table>
          {/if}
      {/if}
<link rel="stylesheet" type="text/css" href="{$stylepath}/minisitepdf.css"/>
<div id="clearboth"></div>
<div id="pdfblock"> 
{foreach item='item' key=index from=$images}
     <span>
             <a href="{modurl modname="ZSELEX" type="user" func="pdfView" pdf=$item.pdf_name shop_id=$smarty.request.shop_id}" target="_blank">
                 <img src="{$baseurl}zselexdata/{$ownerName}/pdfupload/thumb/{$item.pdf_image}.jpg">
             </a>
            
      </span>
 {/foreach}
 </div>
 
 <script type="text/javascript">
 var defwindowajax = new Zikula.UI.Window($('pdfInfo'),{resizable: true });
  </script>
 {jquerycss}
 
 