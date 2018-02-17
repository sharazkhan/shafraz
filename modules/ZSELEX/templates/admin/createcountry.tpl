{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/AutoSuggest.js'}
<link rel="stylesheet" type="text/css" href="modules/ZSELEX/style/autosuggest_inquisitor.css">

{adminheader}
    <div class="z-admin-content-pagetitle">
     {if $item.country_id neq ''}
        <h3>{gt text='Update Country'}</h3>
        {else}
    	<h3>{gt text='Create Country'}</h3>
        {/if}
    </div>
    
    <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitcountry"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[childType]' value="COUNTRY" />
             <input type="hidden" name='formElements[elemId]' value="{$item.country_id}" />
              <input type='hidden' id='temps' name='temps' value=''>
                <input type="hidden" name='formElements[parentCountry]' id="parentcountry" value="{$item.parentcountry_id}" />
             
            <fieldset>

            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.country_name}'   />
            </div>
            
             <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>
          <div class="z-formrow">
			<label>{gt text='Parent Country:'}</label>
			<div>
                     <input name="formElements[parentcountry_list]" type="text" id="parentcountry_list" value="{$item.parentcountry}" size="30" maxlength="1000" onfocus="autoSuggestCountry(this.id, 'listWrap3', 'searchList3', 'parentcountry_list', event);"  onkeyup="autoSuggestCountry(this.id, 'listWrap3', 'searchList3', 'parentcountry_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap3')" /> 
                </div>
                <div class="listWrap" id="listWrap3">
                    <ul class="searchList" id="searchList3">
                    </ul>
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
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_country();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewcountry'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
	</form>
{adminfooter}

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
	
	
</script>