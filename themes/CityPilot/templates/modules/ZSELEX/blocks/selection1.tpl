

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



<script type="text/javascript">
    
    jQuery(document).ready(function(){
        var cache = {};
        //alert(document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=searchres"); exit();
        var frm_source = document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=searchres";  
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

            <div class="FieldSet left">
                <div class="PaddingAdjst" style="z-index:9"> 
                   <input type="textbox" value="{if $search_cookie neq ''}{$search_cookie|cleantext}{else}{gt text='search for...'}{/if}" id="searchfield" onkeyup="searchvalue(this.value)" size="15" onblur="if(this.value=='') this.value='{gt text='search for...'}';" onfocus="if(this.value=='{gt text='search for...'}') this.value='';">
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
                 <span class="Orange" style="float:none; padding-right: 0px; text-align: right; min-width:100px; display: inline-block" >&nbsp;&nbsp;<a href="{modurl modname='ZSELEX' type='user' func='cart'}" id="cartcount">{cartcount} {*DKK <img src="{$themepath}/images/checkout.png" />*}</a></span>
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
<input type='hidden' id='hsearch' name='hsearch' value="{if $search_cookie neq ''}{$search_cookie|cleantext}{/if}">
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


<input type='hidden' id='offset' name='offset' value='0'>
<input type='hidden' id='pageload' name='pageload' value=1>
{*
<input type='text' id='startval' name='startval' value='0'>
<input type='text' id='endval' name='endval' value='5'>
*}

