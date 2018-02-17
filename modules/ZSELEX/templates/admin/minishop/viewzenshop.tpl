

{shopheader}

<div class="z-admin-content-pagetitle">
   
    <!--a href="{modurl modname="ZSELEX" type="admin" func="deleteZenShop" id=$zen_id sid=$shop_id mid=$miniShopId}" class="z-iconlink z-icon-es-delete">{gt text='Unsubscribe from this Service'}</a-->
</div>

    <div class="z-admin-content-pagetitle">
    
    	<h3>{gt text='ZenCart Shop'}</h3>
           
    </div>
     
        
 
    <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="updateZenShop"}" method="post" enctype="multipart/form-data">
          
           <input type='hidden'  name='formElements[shop_id]' id='shop_id' value='{$shop_id}'   />	
           <input type='hidden'  name='formElements[zen_id]' id='zen_id' value='{$zen_id}'   />
		 <div class="z-formrow">
                <label for="ecomDomain">{gt text='Domain'}</label>
                <input type='text'  name='formElements[domain]' id='domain' value='{$domain}'   />	
            </div>
            
            <div class="z-formrow">
                <label for="ecomHost">{gt text='Host'}</label>
                <input type='text'  name='formElements[host]' id='host' value='{$hostname}'   />	
            </div>
            
            <div class="z-formrow">
                <label for="ecomDb">{gt text='Database'}</label>
                <input type='text'  name='formElements[database]' id='database' value='{$dbname}'   />	
            </div>
            
            <div class="z-formrow">
                <label for="ecomUser">{gt text='User Name'}</label>
                <input type='text'  name='formElements[username]' id='username' value='{$username}'   />	
            </div>
            
            <div class="z-formrow">
                <label for="ecomPswrd">{gt text='Password'}</label>
                <input type='text'  name='formElements[password]' id='password' value='{$password}'   />	
            </div>
            <div class="z-formrow">
                <label for="table_prefix">{gt text='Table Prefix'}</label>
                <input type='text'  name='formElements[tableprefix]' id='tableprefix' value='{$table_prefix}'   />	
            </div>
            
                
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_shop();" name="action" value="1" title="{gt text='Save'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save' }
             {gt text='Save'}
            </button>
          
            </div>
	</form>



{adminfooter}