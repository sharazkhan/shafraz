
{adminheader}
<!------------------ VALIDATE PLUGIN --------------------------------------->
{pageaddvar name="stylesheet" value="modules/ZSELEX/javascript/validation/style.css"} 
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation/fabtabulous.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation/validation.js'}
<!---------------------------------------------------------------------->
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/sociallink.js'}

    <div class="z-admin-content-pagetitle">
     {if $item.socl_link_id neq ''}
        <h3>{gt text='Update Social Link'}</h3>
        {else}
    	<h3>{gt text='Create Social Link'}</h3>
        {/if}
    </div>

	<form id="linkform" class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
           
            <input type="hidden" name='formElements[socl_link_id]' value="{$item.socl_link_id}" />
          
          

            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Link Name'}</label>
                <input type='text'  name='formElements[socl_link_name]' id='elemtName' class="required" title="{gt text='Link name required'}" value='{$item.socl_link_name}'   />
            </div>
            
           
            <div class="z-formrow">
                <label for="price">{gt text='Image Name'}</label>
                <input type='text'  name='formElements[socl_image]' id='price' value='{$item.socl_image}'   />
            </div>
            
            
             <div class="z-formrow">
                <label for="status">{gt text='Status'}</label>
                <select id="status" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                     <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
            
        
            
           <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen linksubmit" type="submit" onclick="return validate_adprice();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Link' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewsociallinks'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
	</form>


{adminfooter}

<style>
.validation-advice{
   margin-left:260px;
    }
 </style>