
<div class="z-admin-content-pagetitle">
      <h3>{gt text='Settings'}</h3>
</div>
     
<form class="z-form" id="product_setting_form" name="image_form" action="{modurl modname='ZSELEX' type='admin' func='saveImage' shop_id=$smarty.request.shop_id}" method="post">
       <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            
            <input type="hidden" name="settings[shop_id]" id="shop_id" value="{$shop_id}">

            <div class="z-formrow">
                <label for="shop_info">{gt text='Advertise only selected products'}:</label>
               
                <input type="checkbox" name="settings[advertise_sel_prods]" value="1" {if $settings.advertise_sel_prods eq 1}checked{/if}>
            </div>

       <div class="z-buttons z-formbuttons">
            
            <a href="javascript:closeWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
           
          <button onClick="return saveProductSettings();" id="product_delete"  type="button"  name="action" value="deleteimage" title="{gt text='Delete Image'}">
              <span id="save_bttn" style="display:block">
             {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save'}
             {gt text='Save'}
              </span>
              <span id="save_msg" style="display:none">
                  {gt text='Saving...'}
              </span>
         </button>
       </div>

      </div>
    </form>