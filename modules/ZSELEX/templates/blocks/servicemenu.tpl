
{if $perm}
{if $vars.displayinfo eq 'yes'}
  <a class="infoclass"  id="serviceMenu" href="{modurl modname='ZSELEX' type='info' func='displayInfo' bid=$bid}" title="{$info.title}">
      <img  src="{$baseurl}images/icons/small/info.png">
  </a>
{/if}
{/if}


<ul>
    {if $smarty.request.shop_id neq ''}
      {assign var="SHOPID" value=$smarty.request.shop_id}
      {else}
      {assign var="SHOPID" value=$smarty.request.id} 
    {/if}
     <li><a href='{modurl modname='ZSELEX' type='user' func='newitem' shop_id=$SHOPID}'>Submit Article</a></li>
   
     {foreach from=$services item='service'}
            <li><a href='#'>{$service.plugin_name}</a></li>   
    
    {/foreach}
</ul>



 <script type="text/javascript">
 var defwindowajax = new Zikula.UI.Window($('serviceMenu'),{resizable: true });
 </script>