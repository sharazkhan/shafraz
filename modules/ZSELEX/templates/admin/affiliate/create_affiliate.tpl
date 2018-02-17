
{adminheader}

    <div class="z-admin-content-pagetitle">
     {if $item.aff_id neq ''}
        <h3>{gt text='Update  Affiliate'}</h3>
        {else}
    	<h3>{gt text='Create  Affiliate'}</h3>
        {/if}
    </div>

	<form class="z-form" action="" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
           
            <input type="hidden" name='formElements[aff_id]' value="{$item.aff_id}" />
          
          

            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[aff_name]' id='elemtName' value='{$item.aff_name}'   />
            </div>
            
           
            <div class="z-formrow">
                <label for="price">{gt text='Image Name'}</label>
                <input type='text'  name='formElements[aff_image]' id='price' value='{$item.aff_image}'   />
            </div>
            
        
            
           <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_adprice();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Affiliate' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewaffiliate'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
	</form>


{adminfooter}