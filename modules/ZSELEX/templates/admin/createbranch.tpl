{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}
{adminheader}
    <div class="z-admin-content-pagetitle">
     {if $item.branch_id neq ''}
        <h3>{gt text='Update Branch'}</h3>
        {else}
    	<h3>{gt text='Create Branch'}</h3>
        {/if}
    </div>
    
    <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitbranch"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[childType]' value="BRANCH" />
             <input type="hidden" name='formElements[elemId]' value="{$item.branch_id}" />
            <input type='hidden' id='temps' name='temps' value=''>
             
            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.branch_name}'   />
            </div>
            
             <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>
<!--
            <div id="countries"  class="z-formrow">
                <label for="parentcountry">{gt text='Country'}</label>
                <select name='formElements[parentCountry][]' size="5" multiple id='parentCountry'>
                    <option value='0'>select</option>
                    {foreach item='citem' from=$zcountry}
                    
                    <option value="{$citem.country_id}" {foreach item='sitem' from=$item.parentcountry} {if $sitem.parentId eq $citem.country_id} selected='selected' {/if} {/foreach} > {$citem.country_name} </option>
                    {/foreach}
                </select>
            </div>
            <div id="regions"  class="z-formrow">
                <label for="parentRegion">{gt text='Region'}</label>
                
                <select name='formElements[parentRegion][]' size="5" multiple id='parentRegion'>
                    <option value='0'>select</option>
                    {foreach item='ritem' from=$zregion}
                    <option value="{$ritem.region_id}" {foreach item='sitem' from=$item.parentregion} {if $sitem.parentId eq $ritem.region_id} selected='selected' {/if} {/foreach}> {$ritem.region_name} </option>
                    {/foreach}
                </select>
            </div>
            
            <div  id="cats"   class="z-formrow">
                <label for="parentCity">{gt text='parent City'}</label>
                <select name='formElements[parentCity][]' size="5" multiple id='parentCity'>
                    <option value='0'>select</option>
                    {foreach from=$zcities key=city_id item='cityitem'}
                    <option value="{$cityitem.city_id}" {foreach item='sitem' from=$item.parentcity} {if $sitem.parentId eq $cityitem.city_id} selected='selected' {/if} {/foreach}> {$cityitem.city_name} </option>
                {/foreach}
                </select>
            </div>
            
            <div id="shops"  class="z-formrow">
                <label for="parentShop">{gt text='parent Shop'}</label>
                <select name='formElements[parentShop][]' size="5" multiple id='parentShop'>
                    <option value='0'>select</option>
                    {foreach item='shopitem' from=$zshops}
                    <option value="{$shopitem.shop_id}" {foreach item='sitem' from=$item.parentshop} {if $sitem.parentId eq $shopitem.shop_id} selected='selected' {/if} {/foreach}> {$shopitem.shop_name} </option>
                {/foreach}
                </select>
            </div>
            
            <div  id="ads"   class="z-formrow">
                <label for="parentAd">{gt text='AD'}</label>
                
                <select name='formElements[parentAd][]' size="5" multiple id='parentAd'>
                    <option value='0'>select</option>
                    {foreach from=$zad  item='aditem'}
                    <option value="{$aditem.advertise_id}" {foreach item='sitem' from=$item.parentad} {if $sitem.parentId eq $aditem.advertise_id} selected='selected' {/if} {/foreach}> {$aditem.name} </option>
                {/foreach}
                </select>
            </div>
-->
            <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_branch();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewbranch'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
	</form>
{adminfooter}