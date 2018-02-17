{*
<style>
     a.activehref{color:#e65621;}
     a.deactivehref{color:#717D82;}
 </style>
<div class="ShopRatingDiv">
    <h2 id="ShopName">{$shopitem.shop_name}<br>
       <span>&nbsp;Fredericia</span></h2>
        <!--  Shop Rating show --->
    <div class="Rating">
        <h5>Du kan bedømme butikken</h5>
        <ul>
            <li class="ActiveStar"></li>
            <li class="ActiveStar"></li>
            <li class="FadeStar"></li>
            <li class="FadeStar"></li>
            <li class="FadeStar"></li>
            <li class="Chat"></li>
        </ul>
    </div>
     <!--  Shop Rating show ends   -->
    
     <!--  Shop Menu    -->
    <ul class="TreeView">
        <li class="Parent"><a {if $smarty.request.func eq 'shop'} class="activehref" {else} class="deactivehref"  {/if} href='{modurl modname="ZSELEX" type="user" func="shop" shop_id=$smarty.request.shop_id}'>Forside</li>
        <li  class=""><a {if $smarty.request.func eq 'minishop'} class="activehref" {else} class="deactivehref"  {/if} class="activehref" href='{modurl modname="ZSELEX" type="user" func="minishop" shop_id=$smarty.request.shop_id}'>Produkter</a></li>
        <li  class=""> <a {if $smarty.request.func eq 'findus'} class="activehref" {else} class="deactivehref"  {/if}  class="activehref" href='{modurl modname="ZSELEX" type="user" func="findus" shop_id=$smarty.request.shop_id}'>Find os</a></li> 
    </ul>
     <!--  Shop Menu Ends    --> 
  
     <p>
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur 
        ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla 
        consequat massa quis enim. <br /><br />

        Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer 
        tincidunt. Cras dapibus. Vivamus elementum semper nisi.  <br /><br />

        Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam 
        ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam 
        rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. 
    </p>

    
</div>
    *}
    
     {*{if $shopitem.default_img_frm eq 'fromgallery'}
                    {if $shopitem.image_name neq ''}
                    <a href='{modurl modname='ZSELEX' type='user' func='site'  id=$shopitem.shop_id}' target='_blank'> <img src="{$baseurl}zselexdata/{$shopitem.uname}/minisitegallery/thumb/{$shopitem.image_name}"></a>
                    {/if} 
                 {/if}  
                {if $shopitem.default_img_frm eq 'fromshop'}
                     {if $shopitem.name neq ''}
                    <a href='{modurl modname='ZSELEX' type='user' func='site'  id=$shopitem.shop_id}' target='_blank'> <img src="{$baseurl}zselexdata/{$shopitem.uname}/minisiteimages/thumb/{$shopitem.name}"></a>
                    {/if}
                 {/if}  *}
    
    <p class="ShopRatingDiv">
      
   {$shopitem.shop_info}
    </p>   
      <div>{fblikeservice action='like' url=$url width="500px" height="21px" layout='horizontal' shop_id=$smarty.request.shop_id addmetatags=true metatitle=$shopitem.shop_name metatype="website" metaimage=$shopImage description=$shopitem.shop_info faces=true}</div><br />