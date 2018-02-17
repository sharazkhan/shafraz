{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name="jsgettext" value="module_zselex_js:ZSELEX"}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/selectioncookies.js'} 
<script type="text/javascript" src="modules/ZSELEX/javascript/jqueryautocomplete/js/jquery-ui-1.8.2.custom.min.js"></script>
<link rel="stylesheet" href="modules/ZSELEX/javascript/jqueryautocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation.js'}    
<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/searchlist.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/selections.js'}
<script type="text/javascript" >
     
    
    //jQuery.noConflict();
        
</script>

<link rel="stylesheet" type="text/css" href="{$stylepath}/selectionbox.css"/>
<script type="text/javascript">
    
    jQuery(document).ready(function(){
        var cache = {};
        //alert(document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=searchres"); exit();
        var frm_source = document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=searchres";  
        //var frm_source = [{"value":"Some Name","id":1},{"value":"Some Othername","id":2}];
        jQuery('#searchfield').autocomplete({source:frm_source, minLength:1});
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

<script type="text/javascript">
    var defwindowajax = new Zikula.UI.Window($('miniSiteImageInfo'),{resizable: true });
</script>
<!--<form action='' method='post'>-->
 
   <div class="SearchFields left">
        <div class="PageBreak">
          <div class="FieldSet">
                 <span id="area-div">
                    <select id="area-combo" name="area_id" size="1" >
                        <option value=''>{gt text="search area"}</option>
                        {foreach from=$areas  item='area'}
                        <option value="{$area.area_id}" {if $smarty.cookies.area_cookie eq $area.area_id} selected="selected" {/if}>{$area.area_name|upper}</option>
                        {/foreach}
                    </select>

                </span>
            </div>
            <div class="FieldSet">
                 <span id="cat-div">
                    <select id="cat-combo" name="category_id" size="1" >
                        <option value=''>{gt text="search category"}</option>
                        {foreach from=$category  item='cat'}
                        <option value="{$cat.category_id}" {if $smarty.cookies.category_cookie eq $cat.category_id} selected="selected" {/if}>{$cat.category_name|upper}</option>
                        {/foreach}
                    </select>
                </span>
            </div>

           {* <div class="FieldSet">
                  <span id="branch-div">
                    <select id="branch-combo" name="branch_id" size="1" >
                        <option value=''>{gt text="search branch"}</option>
                        {foreach from=$branchs  item='branch'}
                        <option value="{$branch.branch_id}" {if $smarty.cookies.branch_cookie eq $branch.branch_id} selected="selected" {/if}>{$branch.branch_name|upper}</option>
                        {/foreach}
                    </select>
                </span>
             </div> *}
            {*<div class="FieldSet">
             <span id="shop-div">
              <select id="shop-combo" name="shop_id" size="1" >
                        <option value=''>{gt text="search shop"}</option>
                        {foreach from=$shops  item='shop'}
                        <option value="{$shop.shop_id}" {if $smarty.cookies.shop_cookie eq $shop.shop_id} selected="selected" {/if}>{$shop.shop_name|upper}</option>
                        {/foreach}
               </select>

            </span>
            </div>*}

           <!--  <div class="CurvedBoxSec right">
                 {*
                {if $smarty.request.func eq 'showEvents'}
                <input type="submit" value="VIS RESULTATER"/> 
                {else}    
                <input type="button" value="VIS RESULTATER" onClick="displayBlocks();"/> 
                {/if}
                *}
                 {*  <input type="button" value="VIS RESULTATER" onClick="displayBlocks();"/> *}
                 <input type="button" value="VIS RESULTATER" onClick="document.forms['shopform'].submit();"/>
                
            </div> -->
            <div class="FieldSet left">
                <div class="PaddingAdjst"> 
                   <input type="textbox" value="{if $search_cookie neq ''}{$search_cookie}{else}{gt text='search for...'}{/if}" id="searchfield" onkeyup="searchvalue(this.value)" size="15" onblur="if(this.value=='') this.value='{gt text='search for...'}';" onfocus="if(this.value=='{gt text='search for...'}') this.value='';">
                </div>
            </div> 
             <div class="ButtonSection left NoLeftMargin">
                        <button  class="SearchButtonNew" onclick="document.forms['shopform'].submit();">
                            {gt text="Show shops"}
                            <span class="ArrowShade SlitghtPad" id="Arrow"></span>
                        </button> 
                 &nbsp;<img src="{$themepath}/images/search_close.png" height="10px">  <span class="Orange HoverEffect" style="float:none;cursor:pointer" onClick='resets();'>{gt text="reset search"}</span> 
                 
             </div>
                 <div class="right" style="padding-top:2px;">
                 <span class="Orange" style="float:none; padding-right: 0px; text-align: right;">&nbsp;&nbsp;<a href="{modurl modname='ZSELEX' type='user' func='cart'}">{cartcount} DKK <img src="{$themepath}/images/checkout.png" /></a></span>
                 </div>
                    
          {*  <div class="FieldSet right SlightPadding">
                    <span>
                    <img class="resetImg" src="{$themepath}/images/Refersh.png" alt="Reset" title="Reset" onClick='resets();' height="20" width="20">
                </span> 
            </div>    *}   
           
      </div>
     </div>
           
       
    <!-- </form>  -->
                
<form id="shopform" name="shopform" action="{modurl modname='ZSELEX' type='user' func='shoplists'}" method='post'>               
<input type='hidden' id='hcountry' name='country_id' value='61'>
<input type='hidden' id='hregion' name='region_id' value="{$region_cookie}">
<input type='hidden' id='hcity' name='city_id' value="{$city_cookie}">
<input type='hidden' id='hshop' name='shop_id' value="{$shop_cookie}">
<input type='hidden' id='harea' name='area_id' value="{$area_cookie}">
<input type='hidden' id='hcategory' name='category_id' value="{$category_cookie}">
<input type='hidden' id='hbranch' name='branch_id'  value="{$branch_cookie}">
<input type='hidden' id='hsearch' name='hsearch' value='{if $search_cookie neq ''}{$search_cookie}{/if}'>
</form>

<input type='hidden' id='level'  value=''>
<input type='hidden' id='type'  value=''>
<input type='hidden' id='name'  value=''>

<input type='hidden' id='hcountryname' name='hcountry' value='DENMARK'>
<input type='hidden' id='hregionname' name='hregion' value=''>
<input type='hidden' id='hcity_name' name='hcity' value=''>
<input type='hidden' id='hshop_name' name='hshop' value=''>
<input type='hidden' id='hareaname' name='harea' value=''>


<input type='hidden' id='offset' name='offset' value='0'>
{*
<input type='text' id='startval' name='startval' value='0'>
<input type='text' id='endval' name='endval' value='5'>
*}


