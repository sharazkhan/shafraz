{pageaddvar name='javascript' value='modules/ZSELEX/javascript/AutoSuggest.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}
<link rel="stylesheet" type="text/css" href="modules/ZSELEX/style/autosuggest_inquisitor.css">
{adminheader}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createadprice" class="z-iconlink z-icon-es-add">Create Advertise Price</a>
    <a href="index.php?module=zselex&amp;type=admin&amp;func=viewadprice" class="z-iconlink z-icon-es-new ">View Advertise Price</a>
</div>
    <div class="z-admin-content-pagetitle">
     {if $item.adprice_id neq ''}
        <h3>{gt text='Update  Advertise Price'}</h3>
        {else}
    	<h3>{gt text='Create  Advertise Price'}</h3>
        {/if}
    </div>

	<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitadprice"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[childType]' value="AD" />
            <input type="hidden" name='formElements[elemId]' value="{$item.adprice_id}" />
            <input type='hidden' id='temps' name='temps' value=''>
            <input type="hidden" name='formElements[parentad]' id="parentad" value="{$item.parentadId}" />

            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.name}'   />
            </div>
            
            <div class="z-formrow">
                <label for="elemtName">{gt text='Identifier'}</label>
                <input type='text'  name='formElements[identifier]' id='identifier' value='{$item.identifier}'   />
            </div>
            <div class="z-formrow">
                <label for="price">{gt text='Price'}</label>
                <input type='text'  name='formElements[price]' id='price' value='{$item.price}'   />
            </div>
            
            <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>
            <div class="z-formrow">
                <label for="pricetype">{gt text='Price Type'}</label>
                <select id="pricetype" name="formElements[pricetype]" />
                    <option value="DKK" {if $item.pricetype eq 'DKK'} selected='selected' {/if}>DKK</option>
                    <option value="USD" {if $item.pricetype eq 'USD'} selected='selected' {/if}>USD</option>
                    <option value="EUR" {if $item.pricetype eq 'EUR'} selected='selected' {/if}>EUR</option>
                </select>
            </div>
             <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                    <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
            
           <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_adprice();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewadprice'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
	</form>


{adminfooter}