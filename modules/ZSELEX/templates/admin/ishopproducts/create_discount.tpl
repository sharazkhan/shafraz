{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}


<!------------------ VALIDATE PLUGIN --------------------------------------->
{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/validation/style.css"} 
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation/fabtabulous.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation/validation.js'}
<!---------------------------------------------------------------------->


{pageaddvar name='javascript' value='modules/ZSELEX/javascript/discount_admin.js'}
  <div class="z-admin-content-pagetitle">
        {if $product_options.option_id neq ''}
        <h3>{gt text='Edit Discount'}</h3>
        {else}    
  	<h3>{gt text='Create Discount'}</h3>
        {/if}
  </div>
 
<form id="optForm" class="z-form" action="" method="post" enctype="multipart/form-data">
    <div>
 
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" id="elemId" name="formElements[elemId]" value="{$product_options.option_id}" />
    <input type="hidden" id="shop_id" name="formElements[shop_id]" value="{$smarty.request.shop_id}" />
   
    
    <fieldset>
        <legend>{gt text='Set Option'}</legend>
          
        
        <div class="z-formrow">
            <label for="name">{gt text='Discount Code'}</label>
            <input class="required" title="{gt text='Discount code required'}" type="text" id="name" name="formElements[code]" value="{$discount.discount_code}" />
        </div>
        
         <div class="z-formrow">
            <label for="name">{gt text='Discount Value'}</label>
            <input class="required" title="{gt text='Discount value required'}" type="text" id="discount_value" name="formElements[value]" value="{$discount.discount}" />
        </div>
        <div class="z-formrow">
          <label for="advertise_type">{gt text='Status'}</label>
                <select name='formElements[status]' id='advertise_type' >
                 
                    <option value='1'  {if $discount.status eq '1'} selected='selected' {/if} >{gt text='Active'}</option>
                    <option value='0'  {if $discount.status eq '0'} selected='selected' {/if} >{gt text='InActive'}</option>
                </select>
            </div>
      
        {*<div class="z-formrow">
            <label for="iname" id="iname"><b>{gt text='Add Values'}</b></label>
                
              <table>
                    <tr>
                        <td class="inc">
                           {foreach from=$product_options.values item='value' key='key'}
                            <div>
                              {gt text='value'} : <input class="poptval" autocomplete="off" type="text" name="formElements[optionValues][val][]" value="{$value.option_value}">
                              {gt text='sort order'} : <input size='3' autocomplete="off" type="text" name="formElements[optionValues][sort_order][]" value="{$value.sort_order}">
                               <a class="remove_this btn btn-danger" href="#">  {gt text='remove'}</a>
                            <br>
                           </div>
                            <input autocomplete="off" type="hidden" name="formElements[optionValues][ID][]" value="{$value.option_value_id}">
                            {/foreach}
                        </td>
                    </tr>
                </table>
            
        </div>*}
            
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {*
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        *}
               <button id="zselex_button_submit" onClick="validateOption()"  class="z-btgreen optionSubmit" type="button"  name="action" value="saveevents" title="{gt text='Save Event'}">
                    {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save Event'}
                    {gt text='Save'}
                </button>
        <a href="{modurl modname="ZSELEX" type="admin" func='discount' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    
    </div>
</form>
     <style>
            .validation-advice{
               margin-left:170px;
                }
     </style>
    
    
