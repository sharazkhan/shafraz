
{adminheader}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='jquery.ui'}



<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>
 

{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/dropdownlist/jquery-ui-1.8.13.custom.css"}
{pageaddvar name='stylesheet' value='modules/ZSELEX/javascript/dropdownlist/ui.dropdownchecklist.themeroller.css'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dropdownlist/jquery-1.6.1.min.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dropdownlist/jquery-ui-1.8.13.custom.min.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dropdownlist/ui.dropdownchecklist.js'}


{pageaddvar name='javascript' value='modules/ZSELEX/javascript/searchlistshop.js'}


<style>


#countriesdiv
{
    position: relative;
    margin-left: 360px;
}

#countrytext
{
position: absolute;
top: 0;
left: 0;
z-index: 999;
padding: 0;
margin: 0;
}

#countrylist
{
position: absolute;
top: 0;
left: 0;
padding: 0;
margin: 0;
}

#setselectbox div{
    width:180px;
    border-top:1px solid #ccc;
    cursor:pointer
}

#setselectbox  .close{
    border-top:0px;
    cursor:pointer
}
.setselectbox{
display:none;
width:185px; position:absolute; 
z-index:999;
background-color:#eee;
margin-top: 24px; 

}
.AutoFIll label {
    color: #333333;
    display: block;
    float: left;
    font-weight: normal;
    padding: 0.3em 1% 0.3em 0;
    text-align: right;
    width: 100%;
}
.AutoFIll textarea{ padding:0px;}

.AutoFIll input[type="text"],.AutoFIll textarea {
    padding: 0.09em;
}

 
div.sexy {    margin: 0 0 0 0}

</style>


       <script type="text/javascript" >

             //jQuery.noConflict();
             jQuery(function () {
/*
                //  alert('hii');
               jQuery("#country-combo").ZselexCombo({
                  emptyText: "Choose a country..."
                  //autoFill: true
                  //triggerSelected: true
                });
                
               jQuery("#region-combo").ZselexCombo({
                  emptyText: "Choose a region..."
                  //autoFill: true
                  //triggerSelected: true
                });
                
                 jQuery("#city-combo").ZselexCombo({
                  emptyText: "Choose a city..."
                  //autoFill: true
                  //triggerSelected: true
                });
                
                 jQuery("#area-combo").ZselexCombo({
                  emptyText: "Choose a area..."
                  //autoFill: true
                  //triggerSelected: true
                });
                
                */
                
                 jQuery("#category-combo").ZselexCombo({
                  emptyText: Zikula.__("Select Category...")
                  //autoFill: true
                  //triggerSelected: true
                });
                 jQuery(".mcategory").dropdownchecklist( { emptyText: 'select category' ,  maxDropHeight: 150, width: 150 } );
                 jQuery(".mbranch").dropdownchecklist( { emptyText: 'select branch' ,  maxDropHeight: 150, width: 150 } );

            }); 
            
       
        </script>
        
          <style>
        
        #ajax-container input[type="text"],#ajax-container textarea {
         padding: 0.09em;
         }

          #ajax-container ul {
              margin:0px;
          }
        </style>
    
   
     <div class="z-admin-content-pagetitle">
     {if $item.shop_id neq ''}
        <h3>{gt text='Update Shop'}</h3>
        {else}
    	<h3>{gt text='Create Shop'}</h3>
        {/if}
    </div>
    
     <script type="text/javascript">
            function change(id,id2)
            {
    
                //alert('hiiiii');
                ID = document.getElementById(id);
                ID2 = document.getElementById(id2);
    
                if(ID.style.display == "")
                {
                    ID.style.display = "none";
                }
                else
                {
                    ID.style.display = "";
                }
                /*
                 if(ID2.style.display == "")
                {
                    ID2.style.display = "none";
                }
                else
                {
                    ID2.style.display = "";
                }
                */
            }
        </script>
 
    <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitshop"}" method="post" enctype="multipart/form-data">
		
                <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
                <input type="hidden" name='formElements[childType]' value="SHOP" />
                <input type="hidden" name='formElements[elemId]' value="{$item.shop_id}" />
                <input type='hidden' id='temps' name='temps' value=''>
                <input type="hidden" name='formElements[parentCountry]' id="parentcountry" value="{$item.country_id}" />
                <input type="hidden" name='formElements[parentRegion]' id="parentregion"  value="{$item.region_id}" />
                <input type="hidden" name='formElements[parentCity]' id="parentcity" value="{$item.city_id}" />
                <input type="hidden" name='formElements[parentArea]' id="parentarea" value="{$item.area_id}" />
                <input type="hidden" name='formElements[parentplugin]' id="parentplugin" value="{$item.parentplugin_id}" />
                <input type="hidden" name='formElements[parentcategory]' id="parentcategory" value="{$item.parentcategory_id}" />
                <input type="hidden" name='formElements[parentshop]' id="parentshop" value="{$item.parentshop_id}" />
                <input type="hidden" name='formElements[parentuser]' id="parentuser" value="{$item.parentuser_id}" />
                
                <input type="hidden"  id="parentcountryold" value="{$item.parentcountry_id}" />
                <input type="hidden"  id="parentregionold"  value="{$item.parentregion_id}" />
                <input type="hidden"  id="parentcityold" value="{$item.parentcity_id}" />
                <input type="hidden"  id="parentareaold" value="{$item.parentarea_id}" />
                
                 <input type="hidden" name='formElements[configShopId]' id="configShopId" value="{$item.shoptype_id}" />
                
                 <input type="hidden" name='formElements[hiddenpicture]' id="parentuser" value="{$item.pictures}" />

            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value="{$item.shop_name|cleantext}"   />
            </div>
            
            <div class="z-formrow">
                <label for="elemtName">{gt text='Url Title'}</label>
                <input type='text'  name='formElements[urltitle]' id='urltitle' value="{$item.urltitle|cleantext}"   />
            </div>
            
            <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description|cleantext}</textarea>
            </div>
            
         
          
          
           {*
           <div class="AutoFIll" style="height:45px; margin-top: 8px ">
               <label for="countrylist">{gt text='Country'}</label>
               <div class=""  id="countriesdiv">
                 <input type="text" onfocus="if(this.value=='search...') this.value='';" onblur="if(this.value=='') this.value='search...';" value="search..." name="countrytext" id="countrytext"  onKeyup='getCountry(this.value)' style="width: 155px;height:20px"; autocomplete="off" >
                <div id="replaceSelect">
                 <select name='formElements[countrylist]' id='countrylist' onChange='getVal(this.value , this.options[this.selectedIndex].text)' onclick='outSelect()' style="width: 175px;height:22px">
                    <option value='0'>{gt text='Select'}</option>
                    {foreach from=$countries  item='item'}
                    <option value="{$item.country_id}" > {$item.country_name}</option>
                    {/foreach}
                </select>
               
                </div>
                <div class="setselectbox"  id="setselectbox">
                    
                </div>
                   
              </div>
           
            </div>
            *}
         <div id="hideparents">   
          <div class="z-formrow">
                 <label for="">{gt text='Selected Country'}</label>
               <!-- <span onclick="change('changeparents')">modify parents</span>-->
                 <div><b>{if $item.country_id gt 0}{$item.country_name}{else}<i>{gt text='Nill'}</i></b>{/if}</div>
          </div>
           <div class="z-formrow">
                 <label for="">{gt text='Selected Region'}</label>
               <!-- <span onclick="change('changeparents')">modify parents</span>-->
                
                 <div><b>{if $item.region_id gt 0}{$item.region_name}{else}<i>{gt text='Nill'}</i></b>{/if}</div>
          </div>
           <div class="z-formrow">
                 <label for="">{gt text='Selected City'}</label>
               <!-- <span onclick="change('changeparents')">modify parents</span>-->
                
                 <div><b>{if $item.city_id gt 0}{$item.city_name}{else}<i>{gt text='Nill'}</i></b>{/if}</div>
          </div>
          <div class="z-formrow">
                 <label for="">{gt text='Selected Area'}</label>
               <!-- <span onclick="change('changeparents')">modify parents</span>-->
                
                 <div><b>{if $item.area_id gt 0}{$item.area_name}{else}<i>{gt text='Nill'}</i></b>{/if}</div>
          </div>
            </div>
            <div class="z-formrow">
                 <label for="">{gt text='Change above relations'}</label>
               <!-- <span onclick="change('changeparents')">modify parents</span>-->
                <input onclick="change('changeparents' , 'hideparents')" type="checkbox" name="formElements[changeparents]" value="1">
            </div>
         <div id="changeparents" style="display:none">
            <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                <div style="width:28%; float: left;"><label for="country-combo">{gt text='Countries'}</label></div>
                <div id="ajax-container" style="float:left; padding-left: 10px">
                    <select id="country-combo" name="formElements[country-combo]" size="1" >
                    <option value=''>{gt text='Select'}</option>
                        {foreach from=$countries  item='country'}
                        <option value="{$country.country_id}" >{$country.country_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
                
            </div>
         
            <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                <div style="width:28%; float: left;"><label for="region-combo">{gt text='Regions'}</label></div>
                <div id="ajax-container" class="regions" style="float:left; padding-left: 10px">
                    <select id="region-combo" name="formElements[region-combo]" size="1">
                    <option value=''>{gt text='Select'}</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}" >{$region.region_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
                
            </div>
        
            
              <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                 <div style="width:28%; float: left;"><label for="city-combo">{gt text='City'}</label></div>
                <div id="ajax-container" class="city"  style="float:left; padding-left: 10px">
                    <select id="city-combo" name="formElements[city-combo]" size="1">
                    <option value=''>{gt text='Select'}</option>
                        {foreach from=$cities  item='city'}
                        <option value="{$city.city_id}">{$city.city_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
               
            </div>
                    
                    
              <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                 <div style="width:28%; float: left;"><label for="area-combo">{gt text='Area'}</label></div>
                <div id="ajax-container" class="area" style="float:left; padding-left: 10px">
                    <select id="area-combo" name="formElements[area-combo]" size="1">
                    <option value=''>{gt text='Select'}</option>
                        {foreach from=$areas  item='area'}
                        <option value="{$area.area_id}" >{$area.area_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
               
            </div>
                    </div>     
            

            <div id="shopdetails">
                
           {* <div class="z-formrow">
                <label for="elemtAddrs">{gt text='Address'}</label>
                <textarea  name='formElements[elemtAddrs]' id='elemtAddrs' >{$item.address}</textarea>
            </div>
            
            <div class="z-formrow">
                <label for="elemtTele">{gt text='Telephone'}</label>
                <input type='text'  name='formElements[elemtTele]' id='elemtTele' value='{$item.telephone}'   />
            </div>
            
            <div class="z-formrow">
                <label for="elemtFax">{gt text='Fax'}</label>
                <input type='text'  name='formElements[elemtFax]' id='elemtFax' value='{$item.fax}'   />
            </div>
            
            <div class="z-formrow">
                <label for="elemtEmail">{gt text='Email'}</label>
                <input type='text'  name='formElements[elemtEmail]' id='elemtEmail' value='{$item.email}'   />
            </div>
            
            <div class="z-formrow">
                <label for="opening_hours">{gt text='Opening Hours'}</label>
                <textarea  name='formElements[opening_hours]' id='elemtAddrs' >{$item.opening_hours}</textarea>
            </div>
            </div>*}
            
            <div  id="branches"   class="z-formrow">
                <label for="branch">{gt text='Branch'}</label>
                <select name='formElements[branches][]' id='branch' class="mbranch"  multiple="multiple">
                
                {foreach from=$zbranch  item='zbranchitem'}
                <option value="{$zbranchitem.branch_id}"  {foreach from=$item.branches  item='shop_branch'} {if $zbranchitem.branch_id eq $shop_branch.branch_id} selected="selected" {/if}{/foreach}> {$zbranchitem.branch_name} </option>
                {/foreach}
                </select>
            </div>
             
                <!--
              <div class="z-formrow">
                <label for="parentCategory">{gt text='Category'}</label>
               <div>
                    <input name="formElements[parentcategory_list]" type="text" id="parentcategory_list" value="{$item.parentcategory}" size="30" maxlength="1000" onfocus="autoSuggestCategory(this.id, 'listWrap5', 'searchList5', 'parentcategory_list', event);"  onkeyup="autoSuggestCategory(this.id, 'listWrap5', 'searchList5', 'parentcategory_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap5')" /> 
                </div>
                <div class="listWrap" id="listWrap5">
                    <ul class="searchList" id="searchList5">
                    </ul>
                </div>
            </div>
                -->
                
           {* <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                 <div style="width:28%; float: left;"><label for="category-combo">{gt text='Category'}</label></div>
                <div id="ajax-container" style="float:left; padding-left: 10px">
                    <select id="category-combo" name="formElements[category-combo]" size="1">
                    <option value=''>{gt text='Select'}</option>
                        {foreach from=$categories  item='category'}
                        <option value="{$category.category_id}"  {if $item.cat_id eq $category.category_id} selected='selected' {/if}>{$category.category_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
               
            </div>*}
            
             <div class="z-formrow" id="listCat">
                <label for="prod_cats">{gt text='Categories'}</label>
                <select name='formElements[shop_cats][]' class="mcategory" id='s1a' multiple="multiple">
                 {foreach from=$categories  item='cat'}
                <option value="{$cat.category_id}" {foreach from=$item.categories  item='shop_cat'} {if $cat.category_id eq $shop_cat.category_id} selected="selected" {/if}{/foreach}> {$cat.category_name} </option>
                {/foreach}
                </select>
              </div> 
               
              <div class="z-formrow">
                <label for="typestatus">{gt text='Affiliate'}</label>
                <select id="typestatus" name="formElements[affiliate]" />
                   <option value=''>{gt text='Select Affiliate'}</option>
                       {foreach from=$affiliates item='affiliate'}
                        <option value="{$affiliate.aff_id}"  {if $item.aff_id eq $affiliate.aff_id} selected='selected' {/if}>{$affiliate.aff_name|upper}</option>
                        {/foreach}
                </select>
               </div>
        
           
                <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                      <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                      <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
               </div>
         
   <!--
            {if $item.shop_id neq ''}
              <div class="z-formrow">
                <label for="plugin">Choose Design</label>
                <table width="30%">
                    <tr>
                        <td><b>Theme</b></td>
                        <td><b>Price</b></td>
                        <td><b>Preview</b></td>
                        <td></td>
                    </tr>

                    {foreach  item='design' from=$designs}
                    {assign var="images" value="themes/`$design.plugin_name`/images/preview_large.png"}
                    <tr>
                        <td>
                            {$design.plugin_name}    
                        </td>

                        <td id="price{$design.price}">{$design.price}</td>
                        <td> {if file_exists($images)}
                            <a id="{$design.plugin_id}" rel="imageviewer[galleryDesign]" href="{$baseurl}themes/{$design.plugin_name}/images/preview_large.png"  title="{$design.plugin_name|safetext}">preview</a>
                            {else}
                            not available
                            {/if}

                        </td>
                        <td><input type="radio" {if $shopDesign eq $design.plugin_name} checked="checked" {/if} name="formElements[shopdesign]" id="{$design.plugin_name}"  value="{$design.plugin_name}" /></td>
                    </tr>

                    {/foreach}
                     
                </table>
                          
            </div>
             {/if}
            -->
            </fieldset>

            
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_shop();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region'}
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewshop'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
		
            
             <input type="hidden" name="totalcounts" id="totalcounts" value="0"/>
             <input type="hidden" name="totalcount" id="totalcount" value="0"/>
	</form>
            
            
<input type='hidden' id='hcountry' name='hcountry' value=''>
<input type='hidden' id='hregion' name='hregion' value=''>
<input type='hidden' id='hcity' name='hcity' value=''>
<input type='hidden' id='hshop' name='hshop' value=''>
<input type='hidden' id='harea' name='harea' value=''>
<input type='hidden' id='hcategory' name='hcategory' value=''>
<input type='hidden' id='hbranch'  value=''>

{adminfooter}