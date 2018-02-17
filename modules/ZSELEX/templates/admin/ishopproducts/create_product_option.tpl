{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}


<!------------------ VALIDATE PLUGIN --------------------------------------->
{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/validation/style.css"} 
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation/fabtabulous.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation/validation.js'}
<!---------------------------------------------------------------------->


{pageaddvar name='javascript' value='modules/ZSELEX/javascript/product_admin.js'}
  <div class="z-admin-content-pagetitle">
        {if $product_options.option_id neq ''}
        <h3>{gt text='Edit Option'}</h3>
        {else}    
  	<h3>{gt text='Create Option'}</h3>
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
            <label for="name">{gt text='Name'}</label>
            <input class="required" title="{gt text='Option name required'}" type="text" id="name" name="formElements[name]" value="{$product_options.option_name}" />
        </div>
        
        <div class="z-formrow">
            <label for="type">{gt text='Type'}</label>
            <select id="type" name="formElements[type]" />
             <option value="dropdown" {if $product_options.option_type eq 'dropdown'} selected="selected" {/if}>{gt text='Dropdown'}</option>
             <option value="radio" {if $product_options.option_type eq 'radio'} selected="selected" {/if}>{gt text='Radio'}</option>
             <option value="checkbox" {if $product_options.option_type eq 'checkbox'} selected="selected" {/if}>{gt text='Checkbox'}</option>
           
            </select>
        </div>
        <div class="z-formrow">
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
            
        </div>
            
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {*
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        *}
               <button id="zselex_button_submit" onClick="validateOption()"  class="z-btgreen optionSubmit" type="button"  name="action" value="saveevents" title="{gt text='Save Event'}">
                    {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save Event'}
                    {gt text='Save'}
                </button>
        <a href="{modurl modname="ZSELEX" type="admin" func='productOption' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    
    </div>
</form>
     <style>
            .validation-advice{
               margin-left:170px;
                }
            </style>
    
    
