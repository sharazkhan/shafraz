{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}

{adminheader}
    <div class="z-admin-content-pagetitle">
 {if $item.plugin_id neq ''}
        <h3>{gt text='Update Plugin'}</h3>
        {else}
    	<h3>{gt text='Create Plugin'}</h3>
        {/if}
    </div>
    
	<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitplugin"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name='formElements[childType]' value="PLUGIN" />
            <input type="hidden" name='formElements[elemId]' value="{$item.plugin_id}" />
             <input type='hidden' id='temps' name='temps' value=''>
             <input type="hidden" name='formElements[parentplugin]' id="parentplugin" value="{$item.parentplugin_id}" />
            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.plugin_name}'   />
            </div>
           
            <div class="z-formrow">
             <label for="elemtName">{gt text='Service Identifier'}</label>
             <select name='formElements[identifier]'>
                        <option value=''>{gt text='Select'}</option>
                        {foreach from=$identifiers  item='identifier'}
                        <option value="{$identifier.identifier}"  {if $identifier.identifier eq $item.type} selected='selected' {/if}>{$identifier.identifier}</option>
                        {/foreach}
            </select>
            </div>
                
            <!--
            <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>
             -->
            <!--
             <div class="z-formrow">
                <label for="elemtDesc">{gt text='Display Info'}</label>
               <input type='text'  name='formElements[displayInfo]' id='displayInfo' value='{$item.plugin_name}'   />
            </div>
            -->
            
            
             <div class="z-formrow">
                <label for="qty_based">{gt text='Quantity Based'}</label>
                <div>
                <input type='radio' {if $item.qty_based eq 1} checked="checked" {/if} name='formElements[qty_based]' value='1'>{gt text='Yes'}
                <input type='radio' {if $item.qty_based eq 0} checked="checked" {/if} name='formElements[qty_based]' value='0'>{gt text='No'}
                </div>
              
            </div>
            
            <div class="z-formrow">
                <label for="price">{gt text='Price'}</label>
                <input type='text'  name='formElements[price]' id='price' value='{$item.price}' />
            </div>
            
            <div class="z-formrow">
                <label for="elemtDesc">{gt text='Description'}</label>
                <textarea  name='formElements[elemtDesc]' id='elemtDesc' >{$item.description}</textarea>
            </div>
            
             <div class="z-formrow">
                <label for="price">{gt text='Has Service Dependency'}</label>
                <input type="checkbox"   name="formElements[service_depended]" {if $item.service_depended eq 1}checked="checked"{/if} value="1">
            </div>
            
            <div class="z-formrow">
                <label for="elemtName">{gt text='Depended Services'}</label>
                <div id="ScrollCB" style="height:150px;width:300px;overflow:auto;border:1px solid grey">  
                    <table width="250">
                        <tr>
                            <td>#</td>
                            <td><b>&nbsp;{gt text='Services'}</b></td>
                        </tr>
                        {foreach from=$plugins  item='plugin' key='key'}
                       <tr>
                            <td>
                            <input type="checkbox" id="{$plugin.plugin_id}"  {foreach from=$depend_services  item='dps'}{if $dps.type eq $plugin.type}checked="checked"{/if}{/foreach}  name="formElements[depend_services][{$plugin.plugin_id}]" value="{$plugin.type}">
                            <input type='hidden' name="formElements[servicename][{$plugin.plugin_id}]" value="{$plugin.plugin_name}">
                            </td>
                               
                        <td>
                            <label for="{$plugin.plugin_id}"><b>{$plugin.plugin_name}</b></label>
                        </td>
                      
                        </tr>
                        {/foreach}
                    </table>
                </div> 

            </div>
               
            <div class="z-formrow">
                <label for="price">{gt text='Shop Depended'}</label>
                <input type="checkbox"   name="formElements[shop_depended]" {if $item.shop_depended eq 1}checked="checked"{/if} value="1">
            </div>
           
            
              <div class="z-formrow">
                <label for="elemtName">{gt text='Depended Shop Types'}</label>
                <div id="ScrollCB" style="height:120px;width:250px;overflow:auto;border:1px solid grey">  
                    <table width="200">
                        <tr>
                            <td>#</td>
                            <td><b>&nbsp;Services</b></td>
                        </tr>
                        {foreach from=$shoptypes  item='shoptype' key='key'}
                       <tr>
                            <td>
                            <input type="checkbox" id="{$shoptype.shoptype}"  {foreach from=$depend_shoptypes item='shptype'}{if $shptype eq $shoptype.shoptype}checked="checked"{/if}{/foreach}  name="formElements[depend_shoptype][{$shoptype.shoptype_id}]" value="{$shoptype.shoptype}">
                            <input type='hidden' name="formElements[shoptype][{$shoptype.shoptype_id}]" value="{$shoptype.shoptype}">
                            </td>
                               
                        <td>
                            <label for="{$shoptype.shoptype}"><b>{$shoptype.shoptype}</b></label>
                        </td>
                      
                        </tr>
                        {/foreach}
                    </table>
                </div> 

            </div>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Is Editable'}</label>
                   <input type="checkbox" id="is_editable" name="formElements[is_editable]" value="1" {if $item.is_editable eq 1}checked="checked"{/if}>
             </div>
            {* <div class="z-formrow">
                <label for="elemtName">{gt text='Function name'}</label>
                    <input type='text' name="formElements[func_name]" value="{$item.func_name}">
             </div> *}
              <div class="z-formrow">
             <label for="elemtName">{gt text='Function name'}</label>
             <select name='formElements[func_name]'>
                        <option value=''>{gt text='Select'}</option>
                        <option value="events"  {if $item.func_name eq "events"} selected='selected' {/if}>events</option>
                        <option value="shopsettings"  {if $item.func_name eq "sgopsettings"} selected='selected' {/if}>shopsettings</option>
                        <option value="viewshopevent"  {if $item.func_name eq "viewshopevent"} selected='selected' {/if}>view shopevent</option>
                        <option value="viewadvertise"  {if $item.func_name eq "viewadvertise"} selected='selected' {/if}>view advertisement</option>
                        <option value="viewshoppdf"  {if $item.func_name eq "viewshoppdf"} selected='selected' {/if}>view pdf</option>
                        <option value="viewdotd"  {if $item.func_name eq "viewdotd"} selected='selected' {/if}>view DODT</option>
                        <option value="viewshopgalleryimages"  {if $item.func_name eq "viewshopgalleryimages"} selected='selected' {/if}>view gallery images</option>
                        <option value="viewshopimages"  {if $item.func_name eq "viewshopimages"} selected='selected' {/if}>view minisite images</option>
                        <option value="viewarticleads"  {if $item.func_name eq "viewarticleads"} selected='selected' {/if}>view article ads</option>
                        <option value="viewproducts"  {if $item.func_name eq "viewproducts"} selected='selected' {/if}>view products</option>
                        <option value="paymentgateway"  {if $item.func_name eq "paymentgateway"} selected='selected' {/if}>view payment gateway</option>
                        <option value="shop"  {if $item.func_name eq "shop"} selected='selected' {/if}>view minishop</option>
                        <option value="announcement"  {if $item.func_name eq "announcement"} selected='selected' {/if}>view announcement</option>
                        <option value="viewminisitebanner"  {if $item.func_name eq "viewminisitebanner"} selected='selected' {/if}>view banner</option>
                        <option value="viewemployees"  {if $item.func_name eq "viewemployees"} selected='selected' {/if}>view employees</option>
                        <option value="configureshoptheme"  {if $item.func_name eq "configureshoptheme"} selected='selected' {/if}>view standard themes</option>
                        <option value="sociallinks"  {if $item.func_name eq "sociallinks"} selected='selected' {/if}>Social Links</option>
                            
            </select>
            </div>
             <div class="z-formrow">
                <label for="elemtName">{gt text='Display info'}</label>
                 <select name='blockinfo[displayinfo]'>
                 <option value='no' {if $content.displayinfo eq 'no'} selected="selected" {/if}>{gt text='No'}</option>
                 <option value='yes' {if $content.displayinfo eq 'yes'} selected="selected" {/if}>{gt text='Yes'}</option>
                </select>
            </div>
          {foreach item='language' from=$languages}
        
                <div class="z-formrow" >
                       <label for="infotitle">{gt text="Information title"}(<b>{$language}</b>)</label>
                       <input type="text" id="{$language}" name="blockinfo[{$language}][infotitle]" value="{$content.$language.infotitle}" />
                 </div>  
             
                <div class="z-formrow">
                  <label for="infomessage">{gt text="Information Message"}(<b>{$language}</b>)</label>
                       <textarea id="{$language}" name="blockinfo[{$language}][infomessage]">{$content.$language.infomessage}</textarea>
                </div>
      
          {/foreach}
     
           <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                     <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
           <div class="z-formrow">
               <label for="demo">{gt text='Available for demo'}</label>
               <input type="checkbox" name="formElements[demo]" id="demo" {if $item.demo eq '1'} checked='checked' {/if} value="1"> 
           </div>
           <div class="z-formrow">
                 <label for="demoperiod">{gt text='Demo Period'}({gt text='no: of days'})</label>
                 <input type='text'  name='formElements[demoperiod]' id='demoperiod' value='{$item.demoperiod}' />
               
           </div>    
            <input type="hidden" name="formElements[type]" value="{$item.type}">
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_plugin();" name="action" value="1" title="{gt text='Save this region'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this Region' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewplugin'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
        </div>
		
	</form>

<script type="text/javascript">
	 var plugin_options = {
		script:document.location.pnbaseURL+"/index.php?module=ZSELEX&type=ajax&func=admin_plugin_autocomplete&",
		varname:"input",
		json:true,
		callback: function (obj) { 
		document.getElementById('parentplugin').value = obj.id;
		 }
	};
	var plugin_as_json = new AutoSuggest('parentplugin_list', plugin_options);
	
	
</script>

{adminfooter}