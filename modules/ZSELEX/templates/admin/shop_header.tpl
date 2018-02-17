{*{admincategorymenu}*}
{admincategorymenu}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{*{pageaddvar name='javascript' value='modules/ZSELEX/javascript/basket.js'}*}

<script>
    /*
        jQuery(document).ready(function(){
        //alert('comes here');
        jQuery("#advanceview").click(function(){
            var textval = jQuery(this).text();
            if(jQuery(this).text() == 'switch to advance'){
                setCookie('shop_menu','1','1');
                jQuery('#advanceview').html('switch to basic');
            }
            else{
                setCookie('shop_menu','0','1');
                jQuery('#advanceview').html('switch to advance');
            }
            jQuery("#shop_menu").slideToggle(function(){
                if(jQuery("#func").val()!='shopinnerview'){
                    if(textval=='switch to basic'){
                        var url = "index.php?module=zselex&type=admin&func=shopinnerview&shop_id="+jQuery("#shops_id").val();
                        window.location.href=url;
                    }
             
                }
            });
            jQuery("#admCart").slideToggle();
        });
    });
      
      
    function setCookie(c_name,value,exdays){ // set cookie in js
        var exdate=new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
        document.cookie=c_name + "=" + c_value;
    }
      */
</script>

<input type="hidden" id="func" value="{$func}">
<input type="hidden" id="shops_id" value="{$shop_id}">
<div class="z-admin-content z-clearfix">
    <div class="z-admin-content-modtitle">
        {*
        {if $shop.image neq ''}
        <img src="{$baseurl}zselexdata/shops/thumbs/{$shop.image}" height='50'>
        {/if}
        *}
        {if $shop.default_img_frm eq 'fromgallery'}
            {if $shop.image_name neq ''}
            <div>
                <img src="{$baseurl}zselexdata/{$shop.shop_id}/minisitegallery/thumb/{$shop.image_name}" height='50'>
            </div>
            {/if} 
        {/if}  
        {if $shop.default_img_frm eq 'fromshop'}
            {if $shop.image neq ''}
            <div>
                <img src="{$baseurl}zselexdata/{$shop.shop_id}/minisiteimages/thumb/{$shop.image}" height='50'>
            </div>
            {/if}
        {/if}  
        <h2><a href="{modurl modname="ZSELEX" type="admin" func="shopinnerview" shop_id=$shop_id}">{$shop.shop_name|cleantext}</a></h2>
        {*<div id="advanceview" style="cursor:pointer;color:green">{if $shop_menu_cookie eq 1}switch to basic{else}switch to advance{/if}</div>*}
    </div>
    {* {shopslinks modname=$toplevelmodule type='admin'}   *}
    <div id="shop_menu" {*style="display:{$displayval}"*}>
        {shopslinks modname='ZSELEX' type='admin'} 
    </div>
    <!--
    <div id="admCart" style="padding-right:150px;cursor:pointer" align="right" onClick='displayBasket({$smarty.request.shop_id})' >
      <h4>cart({$count})</h4>
    </div>
    -->
   {* <div id="admCart"  align="right"  >
        <h4><a href='{modurl modname="ZSELEX" type="admin" func="serviceCart"}'>{gt text='Cart (%s)' tag1=$service_count}</a></h4>
    </div> *}
    

{*
    <style>
        .basket_content {
            background-color: white;
            border: 16px solid black;
            left: 25%;
            min-height: 100px;
            overflow: auto;
            position: fixed;
            top: 30%;
            width: 750px;
            z-index: 10002;
        }
        .backshield {
            background-color: #333333;
            height: 200%;
            left: 0;
            opacity: 0.8;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
    </style>
    <div id="light" class="basket_content" style="display:none"></div>
    <div id="backshield" class="backshield" style="height: 2157px;display:none" onClick='closeWindow();'></div>
    *}