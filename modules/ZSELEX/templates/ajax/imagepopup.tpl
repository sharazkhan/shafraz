
<div class="z-admin-content-pagetitle">
      <h3>{gt text='Edit Image'}</h3>
</div>
      <form class="z-form" id="imagedelete_form" name="image_form" action="{modurl modname='ZSELEX' type='admin' func='saveImage' shop_id=$smarty.request.shop_id}" method="post">
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name="image_id" id="image_id" value="{$image.file_id}">
            <input type="hidden" name="shop_id" id="shop_id" value="{$smarty.request.shop_id}">
            <input type="hidden" name="action" value="deleteimage">
      </form>
<form class="z-form" id="image_form" name="image_form" action="{modurl modname='ZSELEX' type='admin' func='saveImage' shop_id=$smarty.request.shop_id}" method="post">
       <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name="image_id" id="image_id" value="{$image.file_id}">
            <input type="hidden" name="shop_id" id="shop_id" value="{$smarty.request.shop_id}">

            <div class="z-formrow">
                <label for="shop_info">{gt text='Image Filename'}:</label>
                <span>{$image.name}</span>
                <input type="hidden" name="image_name" value="{$image.name|cleantext}">
            </div>

            <div class="z-formrow">
                <label for="shop_info">{gt text='Image Name'}:</label>
                <input type="text" name="image_dispname" value="{$image.dispname|cleantext}">
            </div>

            <div class="z-formrow">
                <label for="shop_info">{gt text='Description'}:</label>
                <textarea name="image_desc">{$image.filedescription|cleantext}</textarea>
            </div>
            <div class="z-formrow">
                <label for="shop_info">{gt text='Standard'}:</label>
                <input type="checkbox" name="default_image" value="1" {if $image.defaultImg}checked{/if}>
            </div>
            
            
            <div class="z-formrow">
                <label for="sort_order">{gt text='Sort order'}:</label>
                <input type="text" name="sort_order" value="{$image.sort_order|cleantext}">
            </div>
        <div class="z-formrow">
            <label for="status">{gt text='Status'}</label>
            <select id="status" name="status" />
             <option value="1" {if $image.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
             <option value="0" {if $image.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
            </select>
        </div>
       <div class="z-buttons z-formbuttons">
            {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="saveimage"}
            <a href="javascript:closeWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
            {*{button src="14_layer_deletelayer.png" set="icons/extrasmall" __alt="Delete Image" __title="Delete Image" __text="Delete Image" __name="action" __value="deleteimage"}*}
          <button onClick="return deleteImage();" id="product_delete"  type="button"  name="action" value="deleteimage" title="{gt text='Delete Image'}">
             {img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Delete Image' __title='Delete Image'}
             {gt text='Delete Image'}
         </button>
       </div>

      </div>
    </form>