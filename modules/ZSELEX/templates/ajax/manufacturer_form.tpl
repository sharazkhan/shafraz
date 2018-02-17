
<div class="z-admin-content-pagetitle">
  	<h3>{gt text='Create Manufacturer'}</h3>
</div>
 
        <form onsubmit="return createManufacturer();" class="z-form" action="{modurl modname="ZSELEX" type="admin" func="saveManufacturer"}" method="post" enctype="multipart/form-data">
    <div>
 
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='shop_id' value="{$shop_id}" />
            <input type="hidden" name='owner_id' value="{$owner_id}" />
   
 
        <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='pmnfr_name' value=''   />
            </div>
          
            <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="mnfr_status" name="formElements[status]" />
                     <option value="1" >{gt text='Active'}</option>
                     <option value="0">{gt text='InActive'}</option>
                </select>
            </div>
   
     <div class="z-buttons z-formbuttons">
       {* {button  src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="saveproduct"}*}
         <a href="#" onClick="createManufacturer();"  title="{gt text="Save"}">{img modname=core src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save"} {gt text="Save"}</a>
         <a href="javascript:closeCatWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
       
    </div>
    
    </div>
</form>
