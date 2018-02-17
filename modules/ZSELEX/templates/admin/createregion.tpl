
{adminheader}
{pageaddvar name='javascript' value='jquery'}
    
<link href="modules/ZSELEX/style/combo/sexy-combo.css" rel="stylesheet" type="text/css" />
<link href="modules/ZSELEX/style/combo/sexy/sexy.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="modules/ZSELEX/javascript/combo/jquery.sexy-combo.js"></script>

<script type="text/javascript" >
 jQuery(function () {
 
  jQuery("#country-combo").ZselexCombo({
                  emptyText: Zikula.__("Select Country...")
                  //autoFill: true
                  //triggerSelected: true
                });
 
   }); 
</script>
 <style>
        
        #ajax-container input[type="text"],#ajax-container textarea {
         padding: 0.09em;
         }

          #ajax-container ul {
              margin:0px;
          }
          .AutoFIll textarea{ padding:0px;}

.AutoFIll input[type="text"],.AutoFIll textarea {
    padding: 0.09em;
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

 
div.sexy {    margin: 0 0 0 0}

        </style>
    <div class="z-admin-content-pagetitle">
     {if $item.region_id neq ''}
        <h3>{gt text='Update Region'}</h3>
        {else}
    	<h3>{gt text='Create Region'}</h3>
        {/if}
    </div>
    
    <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitregion"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[childType]' value="REGION" />
            <input type="hidden" name='formElements[elemId]' value="{$item.region_id}" />
            <input type='hidden' id='temps' name='temps' value=''>
            <input type="hidden" name='formElements[parentCountry]' id="parentcountry" value="{$item.parentcountry_id}" />
            <input type="hidden" name='formElements[parentRegion]' id="parentregion"  value="{$item.parentregion_id}" />
                         
            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.region_name}'   />
            </div>
            
             <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>
            
            <!--
            <div id="countries"  class="z-formrow">
                <label for="parentcountry">{gt text='Country'}</label>
               <div>
                     <input name="formElements[parentcountry_list]" type="text" id="parentcountry_list" value="{$item.parentcountry}" size="30" maxlength="1000" onfocus="autoSuggestCountry(this.id, 'listWrap3', 'searchList3', 'parentcountry_list', event);"  onkeyup="autoSuggestCountry(this.id, 'listWrap3', 'searchList3', 'parentcountry_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap3')" /> 
                </div>
                <div class="listWrap" id="listWrap3">
                    <ul class="searchList" id="searchList3">
                    </ul>
                </div>
            </div>-->
                
                
           <div class="AutoFIll" style="height:45px; margin-top: 8px ">
                <div style="width:28%; float: left;"><label for="country-combo">{gt text='Countries'}</label></div>
                <div id="ajax-container" style="float:left; padding-left: 10px">
                    <select id="country-combo" name="formElements[country-combo]" size="1">
                    <option value=''>{gt text='Select Country...'}</option>
                        {foreach from=$countries  item='country'}
                        <option value="{$country.country_id}"  {if $item.country_id eq $country.country_id} selected='selected' {/if}>{$country.country_name|upper}</option>
                        {/foreach}
                    </select>
                </div>
                
            </div>
           
         
                <!--
            <div id="regions"  class="z-formrow">
                <label for="parentRegion">{gt text='Region'}</label>
                
               <div  id='displayregion'>
                    <input name="formElements[parentregion_list]" type="text" id="parentregion_list"  value="{$item.parentregion}" size="30" maxlength="1000" onfocus="autoSuggestRegion(this.id, 'listWrap1', 'searchList1', 'parentregion_list', event);"  onkeyup="autoSuggestRegion(this.id, 'listWrap1', 'searchList1', 'parentregion_list', event);" onkeydown="keyBoardNav(event, this.id);" onblur="outlist('listWrap1')" /> 
                </div>
                <div class="listWrap" id="listWrap1">
                     <ul class="searchList" id="searchList1">
                    </ul>
                </div>
            </div>-->
          
            <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                    <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_region();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewregion'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
	</form>

{adminfooter}