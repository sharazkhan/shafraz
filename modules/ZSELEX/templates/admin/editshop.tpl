{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation.js'}
{adminheader}

<div class="z-admin-content-pagetitle">
   
    <h3>{gt text='Edit Shop'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="updateshop"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" name="formElements[shop_id]"  value='{$zshop.shop_id}' />
    <fieldset>
        


<div class="z-formrow">
<label for="elemtName">{gt text='Name'}</label>
<input type='text'  name='formElements[elemtName]' id='elemtName' value='{$zshop.shop_name}' />
</div>

<div class="z-formrow">
<label for="elemtDesc">{gt text='Description'}</label>
<textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$zshop.description}</textarea>
</div>

<div class="z-formrow">
<label for="elemtAddrs">{gt text='Address'}</label>
<textarea  name='formElements[elemtAddrs]' id='elemtAddrs'  >{$zshop.address}</textarea>
</div>

<div class="z-formrow">
<label for="elemtTele">{gt text='Telephone'}</label>
<input type='text'  name='formElements[elemtTele]' id='elemtTele' value='{$zshop.telephone}'   />
</div>

<div class="z-formrow">
<label for="elemtFax">{gt text='Fax'}</label>
<input type='text'  name='formElements[elemtFax]' id='elemtFax' value='{$zshop.fax}'   />
</div>

<div class="z-formrow">
<label for="elemtEmail">{gt text='Email'}</label>
<input type='text'  name='formElements[elemtEmail]' id='elemtEmail' value='{$zshop.email}'   />
</div>



<div  class="z-formrow">
<label for="parentCity">{gt text='parent City'}</label>
			
                  <select name='formElements[parentCity]' id='parentCity'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach item='item' from=$zcities}
                <option value="{$item.city_id}" {if $zshop.parentId eq $item.city_id} selected="selected"{/if}> {$item.city_name} </option>
                   {/foreach}
              </select>
</div>


 <div class="z-formrow">
    <label for="ecommerce">{gt text='Select Ecommerce'}</label>

                      <select name='formElements[ecommerce]' id='ecommerce' onChange='shopenable(this.value)'>
                      <option value='0'>{gt text='Select'}</option>
                     {foreach item='item' from=$zecommerce}
                    <option value="{$item.shoptype_id}" {if $item.shoptype_id  eq  $zshop.shoptype_id} selected="selected"{/if}> {$item.shopTypeName} </option>
                       {/foreach}
                  </select>
    </div>

<div  id="zshop" style="display:{if $zshop.shoptype_id  eq  '1'} block {else} none {/if};">
     <div class="z-formrow">
    <label for="ecomDomain">{gt text='Domain'}</label>
            <input type='text'  name='formElements[ecomDomain]' id='ecomDomain' value='{$zshop.domain}'   />	
    </div>

    <div class="z-formrow">
    <label for="ecomHost">{gt text='Host'}</label>
            <input type='text'  name='formElements[ecomHost]' id='ecomHost' value='{$zshop.hostname}'   />	
    </div>

    <div class="z-formrow">
    <label for="ecomDb">{gt text='Database'}</label>
            <input type='text'  name='formElements[ecomDb]' id='ecomDb' value='{$zshop.dbname}'   />	
    </div>

    <div class="z-formrow">
    <label for="ecomUser">{gt text='User Name'}</label>
            <input type='text'  name='formElements[ecomUser]' id='ecomUser' value='{$zshop.username}'  />	
    </div>
    <div class="z-formrow">
    <label for="ecomPswrd">{gt text='Password'}</label>
            <input type='text'  name='formElements[ecomPswrd]' id='ecomPswrd' value='{$zshop.password}'   />	
    </div>


    <div class="z-formrow">
    <label for="table_prefix">{gt text='Table Prefix'}</label>
            <input type='text'  name='formElements[table_prefix]' id='table_prefix' value='{$zshop.table_prefix}' />	
    </div>


</div>

  <div class="z-formrow">
<label for="plugins">{gt text='choose plugins'}</label>
		 {foreach item='item' from=$zshop.PLUGINS}	
                <input type="checkbox" value="{$item.plugin_id}" id="plugins" name="formElements[plugins][]" {if $item.parentId eq $zshop.shop_id} checked="checked"{/if} />&nbsp<b>{$item.plugin_name}</b>&nbsp&nbsp
                 {/foreach}
</div>

    </fieldset>
    


<div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewshop'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>

{adminfooter}