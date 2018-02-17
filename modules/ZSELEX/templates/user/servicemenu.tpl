<h4>Services for shop: {$smarty.request.shopName}</h4>

<ul>
    {if $smarty.request.shop_id neq ''}
      {assign var="SHOPID" value=$smarty.request.shop_id}
      {else}
      {assign var="SHOPID" value=$smarty.request.id} 
    {/if}
     <li><a href='{modurl modname='ZSELEX' type='user' func='newitem' shop_id=$SHOPID}'>Submit Article</a></li>
   
     {foreach from=$services item='service'}
      
        {if $service.type eq 'createad'}
         <li><a href='{modurl modname='ZSELEX' type='admin' func='createadvertise' shop_id=$smarty.request.shop_id shopName=$smarty.request.shopName src=front}'>{$service.plugin_name}</a></li>
       {elseif  $service.type eq 'createimage'}
         <li><a href='{modurl modname='ZSELEX' type='admin' func='modifyshop' id=$smarty.request.shop_id}#pic'>{$service.plugin_name}</a></li> 
       {elseif  $service.type eq 'addproducts'}
        <li><a href='{modurl modname='ZSELEX' type='admin' func='addproducts' shop_id=$smarty.request.shop_id}'>{$service.plugin_name}</a></li> 
        {else}
            <li><a href='#'>{$service.plugin_name}</a></li>   
       {/if}
    
    {/foreach}
</ul>