
<!------------------ VALIDATE PLUGIN --------------------------------------->
{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/validation/style.css"} 
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation/fabtabulous.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation/validation.js'}
<!---------------------------------------------------------------------->


{pageaddvar name='javascript' value='modules/ZSELEX/javascript/product_admin.js'}

    <div class="z-admin-content-pagetitle">
         {if $item.manufacturer_id}
        <h3>{gt text='Update Manufacturer'}</h3>
        {else}
            <h3>{gt text='Create Manufacturer'}</h3>
        {/if}
    
    </div>
    
    <form id="mnfrForm" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[elemId]' value="{$item.manufacturer_id}" />
            <input type="hidden" name='formElements[shop_id]' value="{$shop_id}" />
            
          
            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text' name='formElements[elemtName]' class="required" title="{gt text='Manufacturer name required'}" id='elemtName' value='{$item.manufacturer_name}'   />
            </div>
          
            <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>Active</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>InActive</option>
                </select>
            </div>
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen mnfrSubmit" type="submit" onclick="return validate_category();" name="action" value="1" title="{gt text='Save Manufacturer'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save Manufacturer'}
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='manufacturers' shop_id=$shop_id}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
            </fieldset>
        </div>
	</form>
    <div>

</div>



 <style>
            .validation-advice{
               margin-left:170px;
                }
            </style>
