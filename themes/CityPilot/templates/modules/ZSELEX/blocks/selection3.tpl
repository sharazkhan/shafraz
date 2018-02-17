

{*
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/selectioncookies.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jqueryautocomplete/js/jquery-ui-1.8.2.custom.min.js'} 
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation.js'} 
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/combo/jquery.sexy-combo.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/searchlist.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/selections.js'}

{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/jqueryautocomplete/css/smoothness/jquery-ui-1.8.2.custom.css"}
{pageaddvar name="stylesheet" value="modules/ZSELEX/style/combo/sexy-combo.css"}
{pageaddvar name="stylesheet" value="modules/ZSELEX/style/combo/sexy/sexy.css"}
{pageaddvar name="stylesheet" value="themes/CityPilot/style/selectionbox.css"}
*}


<!-- Selection Block  -->
<script type="text/javascript">
    
    jQuery(document).ready(function(){
    var hcountry = jQuery('#hcountry').val();
    var hregion = jQuery('#hregion').val();
    var hcity = jQuery('#hcity').val();
    var harea = jQuery('#harea').val();
    var hshop = jQuery('#hshop').val();
    var hcategory = jQuery('#hcategory').val();
    var hbranch = jQuery('#hbranch').val();
    var hsearch = jQuery('#hsearch').val();
    if (hsearch == Zikula.__('search for...', 'module_zselex_js')) {
        hsearch = '';
    }
    //alert(hsearch);
    var getShopCookie;
    var getRegionCookie;
    var getCityCookie;
    var getAreaCookie;
    var getCategoryCookie;
    var getBranchCookie;
    var getSearchCookie;
    if (hshop > 0) {
        getShopCookie = hshop;
    } else {
        getShopCookie = getCookie('shop_cookie');
    }
    if (hregion > 0) {
        getRegionCookie = hregion;
    }
    else {
        getRegionCookie = getCookie('region_cookie');
    }
    if (hcity > 0) {
        getCityCookie = hcity;
    }
    else {
        getCityCookie = getCookie('city_cookie');
    }
    if (harea > 0) {
        getAreaCookie = harea;
    }
    else {
        getAreaCookie = getCookie('area_cookie');
    }
    if (hcategory > 0) {
        getCategoryCookie = hcategory;
    }
    else {
        getCategoryCookie = getCookie('category_cookie');
    }
    if (hbranch > 0) {
        getBranchCookie = hbranch;
    }
    else {
        getBranchCookie = getCookie('branch_cookie');
    }
    if (hsearch != '') {
        getSearchCookie = hsearch;
    }
    else {
        getSearchCookie = getCookie('search_cookie');
    }
    var hregion = getRegionCookie;
    var hcity = getCityCookie;
    var harea = getAreaCookie;
    var hshop = getShopCookie;
    var hcategory = getCategoryCookie;
    var hbranch = getBranchCookie;
    var hsearch = getSearchCookie;
    var limit = jQuery('#highad_amount').val();
    var params = '';
    if (hshop > 0) {
        params += "&shop_id=" + hshop;
    }
    if (hcountry > 0) {
        params += "&country_id=" + hcountry;
    }
    if (hregion > 0) {
        params += "&region_id=" + hregion;
    }
    if (hcity > 0) {
        params += "&city_id=" + hcity;
    }
    if (harea > 0) {
        params += "&area_id=" + harea;
    }
    if (hcategory > 0) {
        params += "&category_id=" + hcategory;
    }
    if (hbranch > 0) {
        params += "&branch_id=" + hbranch;
    }
    

    //alert(params);
        var cache = {};
        //alert(document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=searchres"); exit();
        var frm_source = document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=searchres" + params;  
        //var frm_source = [{"value":"Some Name","id":1},{"value":"Some Othername","id":2}];
        jQuery('#searchfield').autocomplete({source:frm_source, minLength:1,delay:0});
        //jQuery(".ui-autocomplete").css("z-index","999 !important");
        //jQuery(".ui-autocomplete").css("background","red");
        //jQuery("#searchfield").live('click keyup', function() {
              jQuery(".ui-autocomplete").css("z-index","999");
             
             
        // });
        
    });
    
    
   

</script>


<style>
.HoverEffect:hover{ text-shadow:2px 2px #CCCCCC; }
</style>

{* Purpose of this template: Display products within an external context  *}


{if $vars.displayinfo eq 'yes'}
<a class="infoclass"  id="miniSiteImageInfo" href="{modurl modname='ZSELEX' type='info' func='displayInfo' bid=$bid}" title="{$info.title}">
    <img  src="{$baseurl}images/icons/extrasmall/info.png">
</a>
{/if}

<input type='hidden' id='countryback' name='countryback' value=''>

{*<script type="text/javascript">
    var defwindowajax = new Zikula.UI.Window($('miniSiteImageInfo'),{resizable: true });
</script>*}
<!--<form action='' method='post'>-->
 
   <div class="SearchFields left">
        <div class="PageBreak">
           <div class="fieldset-wrap left clearfix">
            <div class="FieldSet smart-map"> <span id="region-div">
                    <select id="region-combo" name="region_id" size="1" >
                      <option value=''>{gt text="search region"}</option>
                       {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}" {if $smarty.cookies.region_cookie eq $region.region_id} selected="selected" {/if}>{$region.region_name}</option>
                       {/foreach}
                    </select>
                    </span> 
                  </div>
                  <div class="FieldSet smart-map"> 
                    <span id="city-div">
                    <select id="city-combo1" name="city_id" size="1" >
                      <option value=''>{gt text="search city"}</option>
                       {if $smarty.cookies.region_cookie > 0}
                       {foreach from=$cities  item='city'}
                        <option value="{$city.city_id}" {if $smarty.cookies.city_cookie eq $city.city_id} selected="selected" {/if}>{$city.city_name}</option>
                       {/foreach}
                       {/if}
                    </select>
                    </span> 
                  </div>
            <div class="FieldSet">
                 <span id="area-div">
                    <select id="area-combo" name="area_id" size="1" >
                        <option value=''>{gt text="search area"}</option>
                        {foreach from=$areas  item='area'}
                        <option value="{$area.area_id}" {if $smarty.cookies.area_cookie eq $area.area_id} selected="selected" {/if}>{$area.area_name}</option>
                        {/foreach}
                    </select>

                </span>
            </div>
          
            <div class="FieldSet">
                 <span id="cat-div">
                    <select id="cat-combo" name="category_id" size="1" >
                        <option value=''>{gt text="search category"}</option>
                        {foreach from=$category  item='cat'}
                        <option value="{$cat.category_id}" {if $smarty.cookies.category_cookie eq $cat.category_id} selected="selected" {/if}>{$cat.category_name}</option>
                        {/foreach}
                    </select>
                </span>
            </div>

            <div class="FieldSet left">
                <div class="PaddingAdjst" style="z-index:9"> 
                   <input type="textbox" value="{if $search_cookie neq ''}{$search_cookie|cleantext}{else}{gt text='search for...'}{/if}" id="searchfield" onkeyup="searchvalue(this.value)" size="15" onblur="if(this.value=='') this.value='{gt text='search for...'}';" onfocus="if(this.value=='{gt text='search for...'}') this.value='';">
                </div>
            </div> 
           </div>
             <div class="ButtonSection left NoLeftMargin">
                  <button  class="SearchButtonNew" onclick="document.forms['shopform'].submit();">{gt text='Show shops'} <span class="ArrowShade SlitghtPad" id="Arrow"></span> </button>
                  <span class="Orange HoverEffect shop-buttons" onClick='resets();'><i><img src="{$themepath}/images/search_close.png" height="10px"></i>{gt text='reset search'}</span>
                 
             </div>
                <div class="right" style="padding-top:2px;"> 
                  <!--<span class="Orange" style="float:none; padding-right: 0px; text-align: right; min-width:100px; display: inline-block" >&nbsp;&nbsp;<a href="http://z13x.acta-it.dk/cart" id="cartcount"><span id='carts_total' style='float:none; padding:0px; display:inline-block'>0,00 DKK</span><span id='carts_count' style='padding:0px;color:white;display:inline-block;float:none;width:20px;height:18px;background:url(http://z13x.acta-it.dk/themes/CityPilot/images/checkout.png) no-repeat center center; padding-top:9px; text-align:center'>&nbsp;</span> </a></span>--> 
                 <!--<span class="Orange cart-button" style="float:none; padding-right: 0px;padding-bottom: 3px; min-width:90px; display: inline-block;cursor:pointer" onClick="window.location.href='cart.html'">&nbsp;&nbsp;<span id='carts_total' style='float:none; padding:0px; display:inline-block'>0,00 DKK</span><span id='carts_count' style='padding:0px;color:white;display:inline-block;float:none;width:20px;height:18px;background:url(themes/CityPilot/images/checkout.png) no-repeat center center; padding-top:6px; text-align:center'>&nbsp;</span></span>-->
                   <span class="Orange cart-button" onClick="window.location.href='{$baseurl}{modurl modname='ZSELEX' type='user' func='cart'}'">&nbsp;&nbsp;
                       {*
                      <span id='carts_total'>0,00 DKK</span>
                      <span id='carts_count'><img src="themes/CityPilot/images/checkout.png" alt="" /></span>
                      *}
                      {cartcount}
                    </span>
                </div>
          
      </div>
           
     </div>
           
       
    <!-- </form>  -->
                
<form id="shopform" name="shopform" action="{modurl modname='ZSELEX' type='user' func='shoplists'}" method='post'>
<input type='hidden' id='default_country_id' name='default_country_id' value={$country_id}> 
<input type='hidden' id='default_country_name' name='default_country_name' value={$country_name|cleantext}>   
<input type='hidden' id='hcountry' name='country_id' value={$country_id}>
<input type='hidden' id='hregion' name='region_id' value="{$region_cookie|cleantext}">
<input type='hidden' id='hcity' name='city_id' value="{$city_cookie|cleantext}">
<input type='hidden' id='hshop' name='shop_id' value="{$shop_cookie|cleantext}">
<input type='hidden' id='harea' name='area_id' value="{$area_cookie|cleantext}">
<input type='hidden' id='hcategory' name='category_id' value="{$category_cookie|cleantext}">
<input type='hidden' id='hbranch' name='branch_id'  value="{$branch_cookie|cleantext}">
<input type='hidden' id='hbranch_name' name='hbranch_name'  value="{$branchNameCookie|cleantext}">
<input type='hidden' id='hsearch' name='hsearch' value="{if $search_cookie neq ''}{$search_cookie|cleantext}{/if}">
{*<input type="hidden" id="aff_id" name="aff_id" value='{$smarty.request.aff_id|@json_encode}'>*}
<input type="hidden" id="aff_id" name="aff_id" value='{$affiliate_cookie}'>
<input type="hidden" id="aff_name" name="aff_name" value='{$affNameCookie}'>
</form>

<input type='hidden' id='level'  value=''>
<input type='hidden' id='type'  value=''>
<input type='hidden' id='name'  value=''>

<input type='hidden' id='hcountryname' name='hcountry' value={$country_name|cleantext}>
<input type='hidden' id='hregionname' name='hregion' value=''>
<input type='hidden' id='hcity_name' name='hcity' value=''>
<input type='hidden' id='hshop_name' name='hshop' value=''>
<input type='hidden' id='hareaname' name='harea' value=''>
<input type='hidden' id='hcatname' value={$categoryName_cookie|cleantext}>
<input type="hidden" id="current_theme" value="{$current_theme}">
<input type="hidden" id="curr_lang" value="{$thislang}">
{*<input type="hidden" id="aff_id" name="aff_id[]" value='{$smarty.request.aff_id|@json_encode}'>*}


<input type='hidden' id='offset' name='offset' value='0'>
<input type='hidden' id='pageload' name='pageload' value=1>
{*
<input type='text' id='startval' name='startval' value='0'>
<input type='text' id='endval' name='endval' value='5'>
*}


