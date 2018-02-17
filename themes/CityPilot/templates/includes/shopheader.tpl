{* 
{assign var=functions value=','|explode:'findus,site,minishop,productview'}
*}



  {* <script>
    jQuery(function () {
       jQuery("#shops_span").bigText({
          maximumFontSize : 50,
         // textAlign: 'left',
         // limitingDimension: 'both',
        //  verticalAlign: 'left',
         // fontSizeFactor: 1,
          horizontalAlign: 'left'
       });
      // jQuery("#city_span").bigText();
    });
</script>*}
 <style>
     a.activehref{color:#e65621;}
     a.deactivehref{color:#717D82;}
 </style>

{shopdetails shop_id=$smarty.request.shop_id}
 {if $perm}
<div class="OrageEditSec mainHeadEdit">
    <a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}#aInformation">
        <img src="{$themepath}/images/OrageEdit.png">{gt text='Edit Content'}</a></div>
  {/if}
<div class="ShopRatingDiv">
    <div id="ShopName" style="width:630px">
    <!--<span id="shops_span"> {$shop_name|cleantext} </span>
        <br>-->
        <div id="city_div"> <span id="city_span">&nbsp;{$city_name|cleantext}</span> </div>
    </div>
    <!--  Shop Rating show --->
    <div class="Rating" style="padding-top:72px;">
        <!--h5>{gt text='You can rate shop'}</h5-->
        
        {*<ul>
            <li class="ActiveStar"></li>
            <li class="ActiveStar"></li>
            <li class="FadeStar"></li>
            <li class="FadeStar"></li>
            <li class="FadeStar"></li>
            <li class="Chat"></li>
        </ul>*}
         <span align="right">
            {if $aff_id > 0}
              {assign var="imagename" value=$aff_image|replace:' ':'%20'}
              {assign var="image" value="modules/ZSELEX/images/affiliates/`$imagename`"}
                 {if is_file($image)}
                    <div class="ShopHeaderAff">
                        <img src="modules/ZSELEX/images/affiliates/{$aff_image}">
                    </div>
                {/if}
             {/if}   
        </span>
        <div class="ajax" title="{gt text='You can rate shop'}">
        {*{shoprating shop_id=$smarty.request.shop_id user_id=$uid}*}
         {shoprating shop_id=$smarty.request.shop_id user_id=$uid}
        </div>
    </div>
    <!--  Shop Rating show ends   -->

    <!--  Shop Menu    -->
    {*
    <ul class="TreeView">
       
        <li class="Parent"><a {if $smarty.request.func eq 'shop'} class="activehref" {else} class="deactivehref"  {/if} href='{modurl modname="ZSELEX" type="user" func="shop" shop_id=$smarty.request.shop_id}'>Forside</li>
        <li  class=""><a {if $smarty.request.func eq 'shop' OR $smarty.request.func eq 'productview' } class="activehref" {else} class="deactivehref"  {/if} class="activehref" href='{modurl modname="ZSELEX" type="user" func="shop" shop_id=$smarty.request.shop_id}'>Produkter</a></li>
        <li  class=""> <a {if $smarty.request.func eq 'findus'} class="activehref" {else} class="deactivehref"  {/if}  class="activehref" href='{modurl modname="ZSELEX" type="user" func="findus" shop_id=$smarty.request.shop_id}'>Find os</a></li> 
     
    </ul>
    *}
   
      
       <!-- MENU BUTTON -->
        <button class="wsite-nav-button2">Menu</button>
        <div id="minisitemenu_block" class="minisitemenu_block">
        {blockposition name='minisitemenu'}
        </div>
    <!--  Shop Menu Ends    --> 
 </div>
       {*<script type="text/javascript" src="modules/ZSELEX/javascript/bigtext/jquery-bigtext.js"></script>*}