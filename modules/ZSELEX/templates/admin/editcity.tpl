{adminheader}


<div class="z-admin-content-pagetitle">
   
    <h3>{gt text='Edit City'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="updatecity" }" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" name="formElements[city_id]"  value='{$zcity.0.city_id}' />
    <fieldset>
        


<div class="z-formrow">
<label for="elemtName">{gt text='Name'}</label>
<input type='text'  name='formElements[elemtName]' id='elemtName' value='{$zcity.0.city_name}'   />
</div>

<div class="z-formrow">
<label for="elemtDesc">{gt text='Description'}</label>
<textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$zcity.0.description}</textarea>
</div>
<div  class="z-formrow">
<label for="parentCity">{gt text='parent City'}</label>
			
                  <select name='formElements[parentCity]' id='parentCity'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach item='item' from=$zcities}
                <option value="{$item.city_id}" {if $zcity.0.parentId eq $item.city_id} selected="selected"{/if}> {$item.city_name} </option>
                   {/foreach}
              </select>
</div>

    </fieldset>
    


<div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewcity'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>

{adminfooter}