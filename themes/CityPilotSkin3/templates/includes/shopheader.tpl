
 <style>
     a.activehref{color:#e65621;}
     a.deactivehref{color:#717D82;}
 </style>
 
 {shopdetails shop_id=$smarty.request.shop_id}
   {if $perm}
<div class="OrageEditSec mainHeadEdit">
    <a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}">
        <img src="themes/CityPilot/images/OrageEdit.png">{gt text='Edit Content'}</a></div>
  {/if}
    <h2 id="ShopName">{$shop_name}
    <span>&nbsp;{$city_name}</span>
    </h2>
    <!--  Shop Rating show --->
    <div class="Rating">
          
        {*<ul>
            <li class="ActiveStar"></li>
            <li class="ActiveStar"></li>
            <li class="FadeStar"></li>
            <li class="FadeStar"></li>
            <li class="FadeStar"></li>
            <li class="Chat"></li>
        </ul>*}
        <div class="ajax">
        {*{shoprating shop_id=$smarty.request.shop_id user_id=$uid}*}
         {shoprating shop_id=$smarty.request.shop_id user_id=$uid}
        </div>
    </div>
    <!--  Shop Rating show ends   -->

    <!--  Shop Menu    -->
    {*
    <ul class="TreeView">
       
        <li class="Parent"><a {if $smarty.request.func eq 'shop'} class="activehref" {else} class="deactivehref"  {/if} href='{modurl modname="ZSELEX" type="user" func="shop" shop_id=$smarty.request.shop_id}'>Forside</li>
        <li  class=""><a {if $smarty.request.func eq 'minishop' OR $smarty.request.func eq 'productview' } class="activehref" {else} class="deactivehref"  {/if} class="activehref" href='{modurl modname="ZSELEX" type="user" func="minishop" shop_id=$smarty.request.shop_id}'>Produkter</a></li>
        <li  class=""> <a {if $smarty.request.func eq 'findus'} class="activehref" {else} class="deactivehref"  {/if}  class="activehref" href='{modurl modname="ZSELEX" type="user" func="findus" shop_id=$smarty.request.shop_id}'>Find os</a></li> 
     
    </ul>
    *}
   
        <div id="minisitemenu_block">
        {blockposition name='minisitemenu'}
        </div>
    <!--  Shop Menu Ends    --> 

        
        
       {* <h2 id="ShopName">Havens blomster <span>Fredericia</span>

                                </h2>
                                <div class="Rating">

                                    <ul>
                                        <li class="ActiveStar"></li>
                                        <li  class="ActiveStar"></li>
                                        <li  class="FadeStar"></li>
                                        <li  class="FadeStar"></li>
                                        <li  class="FadeStar"></li>
                                        <li  class="Chat"></li>

                                    </ul>

                                </div>
                                <ul class="TreeView">
                                    <li class="Parent"><a>Forside</a></li>
                                    <li  class=""><a>Produkter</a></li>
                                    <li  class=""><a> Find os</a></li> 
                                </ul>*}
       