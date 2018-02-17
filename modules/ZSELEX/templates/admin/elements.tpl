
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation.js'}
{adminheader}
<div class="z-admin-content-pagetitle">
   
    <h3>{gt text='Create Element'}</h3>
</div>

<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitElements"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <fieldset>
        
<div class="z-formrow">
 
	<label for="elmntType">{gt text='Select Type'}</label>
			
                  <select name='formElements[elmntType]' id='elmntType' onChange='validate(this.value);'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach item='item' from=$ztypes}
                <option value="{$item.type_id}"> {$item.type_name} </option>
                   {/foreach}
              </select>
        </div>

<div class="z-formrow">
<label for="elemtName">{gt text='Name'}</label>
<input type='text'  name='formElements[elemtName]' id='elemtName' value=''   />
</div>

<div class="z-formrow">
<label for="elemtDesc">{gt text='Description'}</label>
<textarea  name='formElements[elemtDesc]' id='elemtDesc' ></textarea>
</div>

<div class="z-formrow">
<label for="parentType">{gt text='Parent Type'}</label>
			
                  <select name='formElements[parentType]' id='parentType' onChange='changeParent(this.value);'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach item='item' from=$ztypes}
                
           {if $item.type_id eq '11' or $item.type_id eq '4'}
          
                {php}
                  continue;
                {/php}
              {/if} 
                <option value="{$item.type_id}"> {$item.type_name} </option>
                   {/foreach}
              </select>
        </div>

<div id="shops" style="display:none;" class="z-formrow">
<label for="parentShop">{gt text='parent Shop'}</label>
			
                  <select name='formElements[parentShop]' id='parentShop'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach item='item' from=$zshops}
                <option value="{$item.shop_id}"> {$item.shop_name} </option>
                   {/foreach}
              </select>
</div>

<div  id="cats" style="display:none;"  class="z-formrow">
<label for="parentCity">{gt text='parent City'}</label>
			
                  <select name='formElements[parentCity]' id='parentCity'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach from=$zcities key=city_id item='city_name'}
                <option value="{$city_name.city_id}"> {$city_name.city_name} </option>
                   {/foreach}
              </select>
</div>



<div id="countries" style="display:none;" class="z-formrow">
<label for="parentcountry">{gt text='Country'}</label>
			
                  <select name='formElements[parentCountry]' id='parentCountry'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach item='item' from=$zcountry}
                <option value="{$item.country_id}"> {$item.country_name} </option>
                   {/foreach}
              </select>
</div>


<div  id="ads" style="display:none;"  class="z-formrow">
<label for="parentAd">{gt text='AD'}</label>
			
                  <select name='formElements[parentAd]' id='parentAd'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach from=$zad  item='item'}
                <option value="{$item.advertise_id}"> {$item.name} </option>
                   {/foreach}
              </select>
</div>




<div id="regions" style="display:none;" class="z-formrow">
<label for="parentRegion">{gt text='Region'}</label>
			
                  <select name='formElements[parentRegion]' id='parentRegion'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach item='item' from=$zregion}
                <option value="{$item.region_id}"> {$item.region_name} </option>
                   {/foreach}
              </select>
</div>






<div id="shopdetails"  style="display:none;" >
<div class="z-formrow">
<label for="elemtAddrs">{gt text='Address'}</label>
<textarea  name='formElements[elemtAddrs]' id='elemtAddrs' ></textarea>
</div>

<div class="z-formrow">
<label for="elemtTele">{gt text='Telephone'}</label>
<input type='text'  name='formElements[elemtTele]' id='elemtTele' value=''   />
</div>

<div class="z-formrow">
<label for="elemtFax">{gt text='Fax'}</label>
<input type='text'  name='formElements[elemtFax]' id='elemtFax' value=''   />
</div>

<div class="z-formrow">
<label for="elemtEmail">{gt text='Email'}</label>
<input type='text'  name='formElements[elemtEmail]' id='elemtEmail' value=''   />
</div>

</div>



<div  id="branches" style="display:none;"  class="z-formrow">
<label for="branch">{gt text='Branch'}</label>
			
                  <select name='formElements[branch]' id='branch'>
                  <option value='0'>{gt text='Select'}</option>
                 {foreach from=$zbranch  item='item'}
                <option value="{$item.branch_id}"> {$item.branch_name} </option>
                   {/foreach}
              </select>
</div>







<div  id="ecom" style="display:none;" >
  <div class="z-formrow">
    <label for="ecommerce">{gt text='Select shop type'}</label>

                      <select name='formElements[ecommerce]' id='ecommerce' onChange='shoptype(this.value)'>
                      <option value='0'>{gt text='Select'}</option>
                     {foreach item='item' from=$zecommerce}
                    <option value="{$item.shoptype_id}"> {$item.shopTypeName} </option>
                       {/foreach}
                  </select>
    </div>
</div>



<div id="zshop" style="display:none;" >

   
   <div class="z-formrow">
    <label for="ecomDomain">{gt text='Domain'}</label>
            <input type='text'  name='formElements[ecomDomain]' id='ecomDomain' value=''   />	
    </div>

    <div class="z-formrow">
    <label for="ecomHost">{gt text='Host'}</label>
            <input type='text'  name='formElements[ecomHost]' id='ecomHost' value=''   />	
    </div>

    <div class="z-formrow">
    <label for="ecomDb">{gt text='Database'}</label>
            <input type='text'  name='formElements[ecomDb]' id='ecomDb' value=''   />	
    </div>

    <div class="z-formrow">
    <label for="ecomUser">{gt text='User Name'}</label>
            <input type='text'  name='formElements[ecomUser]' id='ecomUser' value=''   />	
    </div>
    
    <div class="z-formrow">
    <label for="ecomPswrd">{gt text='Password'}</label>
            <input type='text'  name='formElements[ecomPswrd]' id='ecomPswrd' value=''   />	
    </div>


    <div class="z-formrow">
    <label for="table_prefix">{gt text='Table Prefix'}</label>
            <input type='text'  name='formElements[table_prefix]' id='table_prefix' value=''   />	
    </div>



</div>

<div id="pluginsDis" style="display:none;" class="z-formrow">
<label for="plugins">{gt text='choose plugins'}</label>
		 {foreach item='item' from=$zplugins}	
                <input type="checkbox" value="{$item.plugin_id}" id="plugins" name="formElements[plugins][]" />&nbsp<b>{$item.plugin_name}</b>&nbsp&nbsp
                 {/foreach}
</div>






    </fieldset>
    


<div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='modifyconfig'}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    </div>
</form>
{adminfooter}