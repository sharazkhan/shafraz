{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}

<script type="text/javascript" src="modules/ZSELEX/javascript/jqueryautocomplete/js/jquery-ui-1.8.2.custom.min.js"></script>
<link rel="stylesheet" href="modules/ZSELEX/javascript/jqueryautocomplete/css/smoothness/jquery-ui-1.8.2.custom.css" /> 

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation.js'}    
<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/searchlist.js'}
       <script type="text/javascript" >
     
    
        //jQuery.noConflict();
        
        </script>

{*
<link rel="stylesheet" type="text/css" href="modules/ZSELEX/style/autocomplete.css">
*}
<script type="text/javascript">
    
		jQuery(document).ready(function(){
                var cache = {};
                         //alert(document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=searchres"); exit();
                var frm_source = document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=searchres";  
                //var frm_source = [{"value":"Some Name","id":1},{"value":"Some Othername","id":2}];
		jQuery('#hsearch').autocomplete({source:frm_source, minLength:1});
		});

</script>


        <style>
        
        .ajax-container input[type="text"],.ajax-container textarea {
         padding: 0.09em;
         }

          .ajax-container ul {
              margin:0px;
          }
          
          
          div.sexy input {
    background: url("text-bg.gif") repeat-x scroll 0 0 #FFFFFF;
    border: 1px solid #B5B8C8;
    font: 12px/18px tahoma,arial,helvetica,sans-serif;
    height: 13px;
    left: 0;
    margin: 0;
    padding: 1px 3px;
    top: 0;
    vertical-align: middle;
    width: 129px;
}
        </style>

{* Purpose of this template: Display products within an external context  *}

<input type='hidden' id=test1234 value='1234'>
<input type='hidden' id='countrycounts' name='countrycounts' value='{$countryCount}'>
<input id="regionsearchid" type="hidden" name='formElements[parentCountry][]'   />
<input id="citysearchid" type="hidden" name='formElements[parentCountry][]'  />

<input id="shopsearchid" type="hidden" name='formElements[parentCountry][]'  />

<input type='hidden' id='temps' name='temps' value=''>

<input type='hidden' id='fieldid' name='fieldid' value=''>
<input type='hidden' id='strsval' name='strsval' value=''>


{if $vars.displayinfo eq 'yes'}
  <a class="infoclass"  id="miniSiteImageInfo" href="{modurl modname='ZSELEX' type='info' func='displayInfo' bid=$bid}" title="{$info.title}">
      <img  src="{$baseurl}images/icons/extrasmall/info.png">
  </a>
{/if}

<input type='hidden' id='countryback' name='countryback' value=''>

 <script type="text/javascript">
 var defwindowajax = new Zikula.UI.Window($('miniSiteImageInfo'),{resizable: true });
 </script>


<form id='form' name='form' action='' method='get'>
  
     
    
    <div class="SearchFields left">
    <div class="PageBreak">    
        
    <div class="FieldSet">
          {* <input type="texbox" value="" id="search" onkeyup="searchvalue(this.value)">  <input type="button" value="find" onClick="getShopDetails(document.getElementById('hshop').value)"> *}
        {gt text="Search"} : <input type="textbox" value="" id="hsearch" onkeyup="searchvalue(this.value)" size="24"> 
       {* <input type="button" value="find" onClick="getShopDetails(document.getElementById('hshop').value);getAdDetails(); getShopAd(); getEvents();"> *}
        {*<input type="button" value="find" onClick="displayBlocks();">*}
    </div>
       
   {*
            {gt text="Country"}
          
                 <span class="ajax-container" id="country-div">
                <select id="country-combo" name="formElements[country-combo]" size="1" >
                    
                       <option value=''>{gt text="search country"}</option>
                       
                        {foreach from=$countries  item='country'}
                        <option value="{$country.country_id}" {if $smarty.request.country_id eq $country.country_id} selected="selected" {/if}>{$country.country_name|upper}</option>
                        {/foreach}
               </select>
                 </span>
               *} 
           
{*
          {gt text="Region"}          
                <span id="region-div">
                 <select id="region-combo" name="formElements[region-combo]" size="1" >
                    <option value=''>{gt text="search region"}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}" {if $smarty.request.region_id eq $region.region_id} selected="selected" {/if}>{$region.region_name|upper}</option>
                        {/foreach}
               </select>
                </span>
            
        *}

        
           

    

{*
       
          {gt text="City"}
          
                 <span id="city-div">
                 <select id="city-combo" name="formElements[city-combo]" size="1" >
                    <option value='0'>{gt text="search city"}</option>
                        {foreach from=$cities  item='city'}
                        <option value="{$city.city_id}" {if $smarty.request.city_id eq $city.city_id} selected="selected" {/if}>{$city.city_name|upper}</option>
                        {/foreach}
               </select>
                </span>
                
          *}
            
            <div class="FieldSet">
            <span>{gt text="Area"} </span>
               <span id="area-div">
                   
                   <select id="area-combo" name="formElements[area-combo]" size="1" >
                    <option value=''>{gt text="search area"}</option>
                        {foreach from=$areas  item='area'}
                        <option value="{$area.area_id}" {if $smarty.request.area_id eq $area.area_id} selected="selected" {/if}>{$area.area_name|upper}</option>
                        {/foreach}
                   </select>
                   
               </span>
           </div>

           
     
            
         <div class="FieldSet">
            <span>{gt text="Shop"}</span>
               <span id="shop-div">
                    
                    <select id="shop-combo" name="formElements[shop-combo]" size="1" >
                    <option value=''>{gt text="search shop"}</option>
                        {foreach from=$shops  item='shop'}
                        <option value="{$shop.shop_id}" {if $smarty.request.shop_id eq $shop.shop_id} selected="selected" {/if}>{$shop.shop_name|upper}</option>
                        {/foreach}
               </select>
                   
               </span>
           </div>
               
      </div>
               
               <div class="PageBreak">
          
            <div class="FieldSet">
                     <span>{gt text="Category"}</span>
          
               <span id="cat-div">
                   
                    <select id="cat-combo" name="formElements[cat-combo]" size="1" >
                    <option value=''>{gt text="search category"}</option>
                        {foreach from=$category  item='cat'}
                        <option value="{$cat.category_id}" >{$cat.category_name|upper}</option>
                        {/foreach}
                    </select>
                   
               </span>
                    
            </div>
          
      <div class="FieldSet">
          <span> {gt text="Branch"}</span>
               <span id="branch-div">
                    
                     <select id="branch-combo" name="formElements[branch]" size="1" >
                    <option value=''>{gt text="search branch"}</option>
                        {foreach from=$branchs  item='branch'}
                        <option value="{$branch.branch_id}">{$branch.branch_name|upper}</option>
                        {/foreach}
               </select>
                   
               </span>
          
           </div>    
               
               
        <div class="FieldSet">
        <span>
                    <input type="button" value="Reset" onClick='resets();'>
        </span>
            </div>
       </div>
     </div>      
           
   
              
                {*<div align="center" style="cursor:pointer;width:50px" onClick='resets(); getShopDetails(document.getElementById("hshop").value);getAdDetails(); getShopAd(); getEvents();getupcommingEvents("reset"); load();'><font color='blue'>{gt text="reset all"}</font></div>*}
               {* <div align="center" style="cursor:pointer;width:50px" onClick='resets();'><font color='blue'>{gt text="reset all"}</font></div>*}
               
</form>

<input type='hidden' id='hcountry' name='hcountry' value=''>
<input type='hidden' id='hregion' name='hregion' value=''>
<input type='hidden' id='hcity' name='hcity' value=''>
<input type='hidden' id='hshop' name='hshop' value=''>
<input type='hidden' id='harea' name='harea' value=''>
<input type='hidden' id='hcategory' name='hcategory' value=''>
<input type='hidden' id='hbranch'  value=''>

<input type='hidden' id='level'  value=''>
<input type='hidden' id='type'  value=''>
<input type='hidden' id='name'  value=''>

<input type='hidden' id='hcountryname' name='hcountry' value=''>
<input type='hidden' id='hregionname' name='hregion' value=''>
<input type='hidden' id='hcity_name' name='hcity' value=''>
<input type='hidden' id='hshop_name' name='hshop' value=''>
<input type='hidden' id='hareaname' name='harea' value=''>

<input type='hidden' id='hsearch' name='hsearch' value=''>

<input type='hidden' id='offset' name='offset' value='0'>
{*
<input type='text' id='startval' name='startval' value='0'>
<input type='text' id='endval' name='endval' value='5'>
*}
<br><br><br>

