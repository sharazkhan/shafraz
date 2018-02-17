{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}

{adminheader}
    <div class="z-admin-content-pagetitle">
 {if $item.plugin_id neq ''}
        <h3>{gt text='Update Identifier'}</h3>
        {else}
    	<h3>{gt text='Create Identifier'}</h3>
        {/if}
    </div>

	<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func=$func}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
             <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
             <input type="hidden" name='formElements[elemId]' value="{$itemdb.id}" />
             <input type="hidden" name='formElements[selectedidentifier]' value="{$itemdb.identifier}" />
            
            
            <fieldset>
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[name]' id='identifier' value='{$item.name}'  />
            </div>
            
             <div class="z-formrow">
                <label for="elemtName">{gt text='Identifier'}</label>
                <input type='text'  name='formElements[identifier]' id='identifier' value='{$item.identifier}'   />
            </div>
          
             <div class="z-formrow">
                 <label for="description">{gt text='Description'}</label>
                 <textarea id="description" name="formElements[description]">{$item.description}</textarea>
             </div>
      
     
           <div class="z-formrow">
                <label for="status">{gt text='Status'}</label>
                <select id="status" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                     <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
          
          
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="" name="action" value="1" title="{gt text='Create'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Create' }
             {gt text='Save'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewserviceidentifiers'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
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