{shopdetails shop_id=$smarty.request.shop_id}

 {assign var="menuActive1" value=""}
 {assign var="menuActive2" value=""}
 {assign var="menuActive3" value=""}
 {assign var="menuActive4" value=""}
{if $smarty.request.func eq 'site'}
     {assign var="menuActive1" value="active"}
{elseif $smarty.request.func eq 'shop'}     
     {assign var="menuActive2" value="active"}
{elseif $smarty.request.func eq 'pages' OR $smarty.request.func eq 'page'}     
     {assign var="menuActive3" value="active"}
{elseif $smarty.request.func eq 'findus'}     
     {assign var="menuActive4" value="active"}
{/if}

{if $perm}
<a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}#aInformation" class="edit-link"><i class="fa fa-pencil-square" aria-hidden="true"></i>
    {gt text="Edit Content"}
</a>
{/if}
<nav class="navbar shop-navigation">
    <div class="navbar-header pull-left">
        <button type="button" class="navbar-toggle collapsed shop-nav-btn" data-toggle="collapse" data-target="#shop-navigation" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse shop-nav-links pull-left" id="shop-navigation">
        <ul class="nav navbar-nav">
            <li class="{$menuActive1}"><a href="{modurl  modname='ZSELEX' type='user' func='site' id=$smarty.request.shop_id}">{gt text='Home'}</a></li>
            <li class="{$menuActive2}"><a href="{modurl modname="ZSELEX" type="user" func="shop" shop_id=$smarty.request.shop_id}">{gt text='Products'}</a></li>
            <li class="{$menuActive3}"><a href="{modurl modname="ZTEXT" type="user" func="pages" shop_id=$smarty.request.shop_id}">{gt text='Pages'}</a></li>
            <li class="{$menuActive4}"><a href="{modurl modname="ZSELEX" type="user" func="findus" shop_id=$smarty.request.shop_id}">{gt text='Find Us'}</a></li>
        </ul>
    </div>
    <div class="shop-nav-right pull-right clearfix">
        {*<div class="rating">
            <a href="#"><img src="{$themepath}/images/activestar.png" alt=""></a>
            <a href="#"><img src="{$themepath}/images/activestar.png" alt=""></a>
            <a href="#"><img src="{$themepath}/images/activestar.png" alt=""></a>
            <a href="#"><img src="{$themepath}/images/inactivestar.png" alt=""></a>
            <a href="#"><img src="{$themepath}/images/inactivestar.png" alt=""></a>
        </div>*}
       {* <div class="review-count">20</div>*}
         <div class="ajax rating" title="{gt text='You can rate shop'}">
       
         {shoprating shop_id=$smarty.request.shop_id user_id=$uid}
        </div>
        {if $aff_id > 0}
            {assign var="imagename" value=$aff_image|replace:' ':'%20'}
            {assign var="image" value="modules/ZSELEX/images/affiliates/`$imagename`"}
            {if is_file($image)}
                <div class="shop-icon">
                    <img src="{$baseurl}/modules/ZSELEX/images/affiliates/{$aff_image}" alt="" width="39" height="40">
                </div>
            {/if}
        {/if}

    </div>
</nav>