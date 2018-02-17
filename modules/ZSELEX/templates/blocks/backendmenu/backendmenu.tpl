
<script type="text/javascript">
    jQuery.noConflict();
    //<![CDATA[ 
   jQuery(document).ready(function() {
        //alert('hiii');
        jQuery('#backendmenu2').hide();
        jQuery('#backendmenu3').hide();
        jQuery('#mynavbar a').click(function() {
            //jQuery('#mynavbar li').css('font-weight', 'italic');
            jQuery(jQuery('#mynavbar a')).css('color', '');
            jQuery('#backendmenu1').hide();
            jQuery('#backendmenu2').hide();
            jQuery('#backendmenu3').hide();
            jQuery(jQuery(this).attr('href')).show();
            jQuery(jQuery(this)).css('color', '#DD511D');
            return false;
        });
    });
    //]]>  
</script>
<div id="mynavbar">
    <ul>
        <li><a href="#backendmenu1">{gt text='My Account'}</a></li>
        <li><a href="#backendmenu2">{gt text='Services'}</a></li>
        <li><a href="#backendmenu3">{gt text='Minisite'}</a></li>
    </ul>
</div>
<div class="z-block z-blockposition-lowertopmenu z-bkey-minisitemenu z-bid-78 ClearSec">
    <div id="navcontainer_78" class="navcontainer">

        <div id="backendmenu1">
            <ul class="navlist">
                <li><a href="{modurl modname='users' type='user' func='changePassword'}">{gt text='Change Password'}</a></li>
                <li><a href="{modurl modname='users' type='user' func='changeLang'}">{gt text='Change Language'}</a></li>
                <li><a href="{modurl modname='users' type='user' func='changeEmail'}">{gt text='Change Email'}</a></li>
            </ul>
        </div>
        <div id="backendmenu2">
            <ul class="navlist">
                <li><a href="">{gt text='Bundles'}</a></li>
                <li><a href="{modurl modname='ZSELEX' type='admin' func='quickbuy' shop_id=$smarty.request.shop_id}">{gt text='Purchase'}</a></li>
                <li><a href="{modurl modname='ZSELEX' type='admin' func='editservices' shop_id=$smarty.request.shop_id}">{gt text='Edit Services'}</a></li>
            </ul>
        </div>
        <div id="backendmenu3">
            <ul class="navlist">
                <li><a href="{modurl modname='ZSELEX' type='admin' func='shopsettings' shop_id=$smarty.request.shop_id}">{gt text='Frontpage'}</a></li>
                <li><a href="{modurl modname='ZSELEX' type='admin' func='products' shop_id=$smarty.request.shop_id}">{gt text='Products'}</a></li>
                <li><a href="{modurl modname='ZSELEX' type='admin' func='shopsettings' shop_id=$smarty.request.shop_id}">{gt text='Find Us'}</a></li>
                <li><a href="{modurl modname='ZSELEX' type='admin' func='events' shop_id=$smarty.request.shop_id}">{gt text='Events'}</a></li>
            </ul>
        </div>

    </div>
</div>

<style>
 .ClearSec { clear: both; margin-right: 35px; margin-right: 94px; padding-top: 8px;}
</style>