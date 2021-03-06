
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/AutoSuggest.js'}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/datepicker/protoplasm.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/datepicker/datepicker.js'}
{pageaddvar name='stylesheet' value='modules/ZSELEX/style/datepicker/datepicker.css'}
{pageaddvar name='stylesheet' value='modules/ZSELEX/style/datepicker/protoplasm.css'}

<link rel="stylesheet" type="text/css" href="modules/ZSELEX/style/autosuggest_inquisitor.css">

<link rel="stylesheet" type="text/css" href="modules/ZSELEX/style/date/css/datepicker.css">
{shopheader}
   <div class="z-admin-content-pagetitle">
        {if $item.advertise_id neq ''}
        <h3>{gt text='Update Advertisement'}</h3>
        {else}
    	<h3>{gt text='Create Advertisement'}</h3>
        {/if}
    </div>

	<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitadvertiseuser"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[childType]' value="AD" />
            <input type="hidden" name='formElements[elemId]' value="{$item.advertise_id}" />
            <input type='hidden' id='temps' name='temps' value=''>
            <input type="hidden" name='formElements[parentCountry]' id="parentcountry" value="{$item.country_id}" />
            <input type="hidden" name='formElements[parentRegion]' id="parentregion"  value="{$item.region_id}" />
            <input type="hidden" name='formElements[parentCity]' id="parentcity" value="{$item.city_id}" />
            <input type="hidden" name='formElements[parentshop]' id="parentshop"  value="{if $shop_id_fromlist neq  ''}{$shop_id_fromlist}{else}{$item.shop_id}{/if}"/>

            <input type="hidden" name='formElements[shop_id]' id="shop_id" value="{$shop_id}" />
            
            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.name}'   />
              
            </div>
            
            <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>
            
            <div class="z-formrow">
                <label for="keywords">{gt text='Keywords'}</label>
                <textarea  name='formElements[keywords]' id='elemtDesc' >{$item.keywords}</textarea>
            </div>

    {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
          {*
            <div id="shops"  class="z-formrow">
                <label for="parentShop">{gt text='Shop'}</label>
                 <div>
                    <input name="formElements[parentshop_list]" type="text" id="parentshop_list" value="{if $shop_id_fromlist neq  ''}{$shopName_fromlist}{else}{$item.shop_name}{/if}" size="30" maxlength="1000"  /> 
                </div>
                
            </div>
           *}     
                
                 <div class="z-formrow">

      <label for="advertise_type">{gt text='Select AD Type'}</label>
                <select name='formElements[advertise_type]' id='advertise_type' >
                   <option value=''>select type</option>
                    <option value='productAd'  {if $item.advertise_type eq 'productAd'} selected='selected' {/if} >{gt text='Product AD'}</option>
                     <option value='shopAd'  {if $item.advertise_type eq 'shopAd'} selected='selected' {/if} >{gt text='Shop AD'}</option>
                     
                </select>
   </div>


   <div class="z-formrow">

      <label for="level">{gt text='Select Level'}</label>
                <select name='formElements[level]' id='level' onChange='shopAdLevel(this.value)'>
                   <option value=''>{gt text='Select Level'}</option>
                    <option value='COUNTRY'  {if $item.level eq 'COUNTRY'} selected='selected' {/if} >{gt text='Country'}</option>
                     <option value='REGION'  {if $item.level eq 'REGION'} selected='selected' {/if} >{gt text='Region'}</option>
                      <option value='CITY'  {if $item.level eq 'CITY'} selected='selected' {/if} >{gt text='City'}</option>
                      <option value='SHOP'  {if $item.level eq 'SHOP'} selected='selected' {/if} >{gt text='Shop'}</option>
                </select>
   </div>

          
             <div id="countries"  class="z-formrow" style="display:{if $item.level  eq  'COUNTRY'} block {else} none {/if};">
                <label for="parentcountry">{gt text='Country'}</label>
                <div>
                     <input name="formElements[parentcountry_list]" type="text" id="parentcountry_list" value="{$item.country_name}" size="30" maxlength="1000" onfocus="autoSuggestCountry(this.id, 'listWrap3', 'searchList3', 'parentcountry_list', event);"  onkeyup="autoSuggestCountry(this.id, 'listWrap3', 'searchList3', 'parentcountry_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap3')" /> 
                </div>
               
            </div>
            <div id="regions"  class="z-formrow" style="display:{if $item.level  eq  'REGION'} block {else} none {/if};">
                <label for="parentRegion">{gt text='Region'}</label>
                <div  id='displayregion'>
                    <input name="formElements[parentregion_list]" type="text" id="parentregion_list"  value="{$item.region_name}" size="30" maxlength="1000" onfocus="autoSuggestRegion(this.id, 'listWrap1', 'searchList1', 'parentregion_list', event);"  onkeyup="autoSuggestRegion(this.id, 'listWrap1', 'searchList1', 'parentregion_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap1')" /> 
                </div>
                
            </div>
            
            <div  id="cats"   class="z-formrow" style="display:{if $item.level  eq  'CITY'} block {else} none {/if};">
                <label for="parentCity">{gt text='City'}</label>
                <div>
                    <input name="formElements[parentcity_list]" type="text" id="parentcity_list" value="{$item.city_name}" size="30" maxlength="1000" onfocus="autoSuggestCity(this.id, 'listWrap2', 'searchList2', 'parentcity_list', event);"  onkeyup="autoSuggestCity(this.id, 'listWrap2', 'searchList2', 'parentcity_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap2')" /> 
                </div>
                
            </div>

          <div id="adprices"  class="z-formrow">
                <label for="parentadprice">{gt text='Advertise Price'}</label>
                <select name='formElements[adprice_id]' id='parentadprice'>
                    <option value='0'>{gt text='Select'}</option>
                    {foreach item='citem' from=$zadprice}
                      <option value="{$citem.adprice_id}+{$citem.price}" {if $item.adprice_id eq $citem.adprice_id} selected='selected' {/if}  > {$citem.name} ({$citem.price}) </option>
                    {/foreach}
                </select>
            </div>


          <div class="z-formrow">
                <label for="maxviews">{gt text='Max Views'}</label>
                <input type='text'  name='formElements[maxviews]' id='maxviews' value='{$item.maxviews}'   />
               
            </div>

          <div class="z-formrow">
                <label for="maxclicks">{gt text='Max Clicks'}</label>
                <input type='text'  name='formElements[maxclicks]' id='maxclicks' value='{$item.maxclicks}'   />
               
            </div>

           <div class="z-formrow">
                <label for="startdate">{gt text='Start Date'}</label>
                <input type='text'  name='formElements[startdate]' id='startdate' class='startdate' value='{$item.startdate}' />
               
            </div>


          <div class="z-formrow">
                <label for="enddate">{gt text='End Date'}</label>
                <input type='text'  name='formElements[enddate]' id='enddate'  class='enddate' value='{$item.enddate}' />
               
            </div>


             <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>Active</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>InActive</option>
                </select>
            </div>
             {/securityutil_checkpermission_block}
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_advertise();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewadvertise' shop_id=$shop_id}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
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
	
 var shop_options = {
		script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_shop_autocomplete&",
		varname:"input",
		json:true,
		callback: function (obj) { 
		document.getElementById('parentshop').value = obj.id;
		 }
	};
	var shop_as_json = new AutoSuggest('parentshop_list', shop_options);



$('#startdate').DatePicker({
	format:'Y/m/d',
	date: $('#startdate').val(),
	current: $('#startdate').val(),
	starts: 1,
	position: 'r',
	onBeforeShow: function(){
		$('#startdate').DatePickerSetDate($('#startdate').val(), true);
	},
	onChange: function(formated, dates){
		$('#startdate').val(formated);
		if ($('#closeOnSelect input').attr('checked')) {
			$('#startdate').DatePickerHide();
		}
	}
});



$('#enddate').DatePicker({
	format:'Y/m/d',
	date: $('#enddate').val(),
	current: $('#enddate').val(),
	starts: 1,
	position: 'r',
	onBeforeShow: function(){
		$('#enddate').DatePickerSetDate($('#enddate').val(), true);
	},
	onChange: function(formated, dates){
		$('#enddate').val(formated);
		if ($('#closeOnSelect input').attr('checked')) {
			$('#enddate').DatePickerHide();
		}
	}
});
	
</script>



<script type="text/javascript" language="javascript">

  var thisbaseurl='{{$baseurl}}';


//Control.DatePicker.DateFormat.format(date, format)

   Protoplasm.use('datepicker')
 .transform('.startdate')
  .transform('.enddate')
document.on('dom:loaded', function () {
 $('daterangepanel').insert(new Control.DatePicker.Panel({
  range: true,
  monthCount: 3,
                icon:thisbaseurl+'zselexdata/calendar.png',
  onSelect: function(start, end) {
   $('daterangelabel').update('<i>'+Zikula.__f('Start: %s, End: %s',Array(start,end))+'</i>');
  }
  }).element);

});
</script>

{adminfooter}