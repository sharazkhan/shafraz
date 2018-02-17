

{adminheader}
{pageaddvar name='javascript' value='jquery'}
       
<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>


  <script type="text/javascript" >
             //jQuery.noConflict();
             jQuery(function () {

                //  alert('hii');
               jQuery("#country-combo").ZselexCombo({
                  emptyText: Zikula.__("Select Country...")
                  //autoFill: true
                  //triggerSelected: true
                });
                
               jQuery("#region-combo").ZselexCombo({
                  emptyText: Zikula.__("Select Region...")
                  //autoFill: true
                  //triggerSelected: true
                });
                
               
               

            }); 
            
        
        </script>
        
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

   #ajax-container input[type="text"],#ajax-container textarea {
         padding: 0.09em;
         }

          #ajax-container ul {
              margin:0px;
          }

</style>

    <div class="z-admin-content-pagetitle">
     {if $item.city_id neq ''}
        <h3>{gt text='Update City'}</h3>
        {else}
    	<h3>{gt text='Create City'}</h3>
        {/if}
    </div>
    
    <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitcity"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[childType]' value="CITY" />
            <input type="hidden" name='formElements[elemId]' value="{$item.city_id}" />
            <input type="hidden" name='formElements[parentCountry]' id="parentcountry" value="{$item.parentcountry_id}" />
            <input type="hidden" name='formElements[parentRegion]' id="parentregion"  value="{$item.parentregion_id}" />
            <input type="hidden" name='formElements[parentCity]' id="parentcity" value="{$item.parentcity_id}" />
            
             <input type='hidden' id='temps' name='temps' value=''>
            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.city_name}'   />
            </div>
            
             <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>
             <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                <div style="width:28%; float: left;"><label for="country-combo">{gt text='Countries'}</label></div>
                <div id="ajax-container" style="float:left; padding-left: 10px">
                    <select id="country-combo" name="formElements[country-combo]" size="1">
                    <option value=''>search</option>
                        {foreach from=$countries  item='country'}
                        <option value="{$country.country_id}"  {if $item.country_id eq $country.country_id} selected='selected' {/if}>{$country.country_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
                
            </div>
           
            <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                <div style="width:28%; float: left;"><label for="region-combo">{gt text='Regions'}</label></div>
                <div id="ajax-container" class="regions" style="float:left; padding-left: 10px">
                    <select id="region-combo" name="formElements[region-combo]" size="1">
                    <option value=''>search</option>
                        {foreach from=$regions  item='region'}
                        <option value="{$region.region_id}"  {if $item.region_id eq $region.region_id} selected='selected' {/if}>{$region.region_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
                
            </div>
        
            <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_city();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region'}
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewcity'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>

         
	</form>

<script type="text/javascript">
	var country_options = {
		script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_country_autocomplete&",
		varname:"input",
		json:true,
		callback: function (obj) { 
		document.getElementById('parentcountry').value = obj.id;
		 }
	};
	var as_json = new AutoSuggest('parentcountry_list', country_options);

        var region_options = {
		script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_region_autocomplete&",
		varname:"input",
		json:true,
		callback: function (obj) { 
		document.getElementById('parentregion').value = obj.id;
		 }
	};
	var region_as_json = new AutoSuggest('parentregion_list', region_options);

         var city_options = {
		script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_city_autocomplete&",
		varname:"input",
		json:true,
		callback: function (obj) { 
		document.getElementById('parentcity').value = obj.id;
		 }
	};
	var city_as_json = new AutoSuggest('parentcity_list', city_options);
	
	
</script>
{adminfooter}