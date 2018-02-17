

{shopheader}
<br>
   <div>
    <b>Redirecting to the configured minishop config Please Wait.....</b>
   </div>
    
   {if $type eq 'zSHOP'}
        {php}
    header("Refresh: 4;url='" . ModUtil::url('ZSELEX', 'admin', 'viewZenShop' , array('shop_id'=>$_REQUEST['shop_id'])));
        {/php}
    
   {elseif $type eq 'iSHOP'}
         {php}
    header("Refresh: 4;url='" . ModUtil::url('ZSELEX', 'admin', 'viewproducts' , array('shop_id'=>$_REQUEST['shop_id'])));
        {/php}
   {/if}

{adminfooter}