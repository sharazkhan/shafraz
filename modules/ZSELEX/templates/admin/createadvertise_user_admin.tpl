
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

	<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitadvertiseuseradmin"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
          
         
            <input type="hidden" name='formElements[elemId]' value="{$item.advertise_id}" />
           

            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.name}'/>
              
            </div>
            
            <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>

   
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_advertise();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewadvertise' shop_id=$shop_id}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
	</form>


{adminfooter}