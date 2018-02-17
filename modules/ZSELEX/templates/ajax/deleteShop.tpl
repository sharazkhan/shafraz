
<div class="z-admin-content-pagetitle">
      <h3>{gt text='Delete Shop'}</h3>
</div>
    
<form class="z-form" id="image_form" name="image_form" action="{modurl modname='ZSELEX' type='admin' func='saveImage' shop_id=$smarty.request.shop_id}" method="post">
       <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            
            <input type="hidden" name="shop_id" id="shop_id" value="{$shopId}">

           
            <div class="z-formrow">
                <label for="shop_info">{gt text='Reason'}:</label>
                <textarea name="delete_desc" id="delete_desc"></textarea>
            </div>
            
       <div class="z-buttons z-formbuttons">
           
            <a href="javascript:closeWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
           
          <button onClick="deleteShopConfirm()" id="shop_delete"  type="button"  name="action" value="deleteimage" title="{gt text='Delete'}">
             {img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Delete' __title='Delete'}
             {gt text='Delete'}
         </button>
       </div>

      </div>
    </form>