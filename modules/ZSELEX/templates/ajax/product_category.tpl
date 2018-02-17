
<div class="z-admin-content-pagetitle">
  	<h3>{gt text='Products Category'}</h3>
</div>
 
        <form onsubmit="return createCategory();" class="z-form" action="{modurl modname="ZSELEX" type="admin" func="saveCategory"}" method="post" enctype="multipart/form-data">
    <div>
 
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[elemId]' value="{$item.prd_cat_id}" />
            <input type="hidden" name='formElements[shop_id]' value="{$smarty.request.shop_id}" />
            <input type="hidden" name='formElements[source]' value="product" />
            <input type="hidden" name='shop_id' value="{$shop_id}" />
            <input type="hidden" name='owner_id' value="{$owner_id}" />
   
 
        <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='pcat_name' value='{$item.prd_cat_name}'   />
            </div>
          
            <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="status" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>Active</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>InActive</option>
                </select>
            </div>
   
     <div class="z-buttons z-formbuttons">
       {* {button  src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="saveproduct"}*}
         <a href="#"  onClick="createCategory();" title="{gt text="Save"}">{img modname=core src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save"} {gt text="Save"}</a>
         <a href="javascript:closeCatWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
       
    </div>
    
    </div>
</form>
