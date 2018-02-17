<form id="page_popup" class="z-form" action="" method="post" enctype="multipart/form-data">
    <div>
      <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
      <input type="hidden" id="shop_id" name="formElements[shop_id]" value="{$shop_id}" />
      <input type="hidden" id="elemId" name="elemId" value="{$item.text_id}" />
      <input type="hidden" id="div_id" name="div_id" value="{$div_id}" />
      <input type="hidden" id="ztext_bodytext_hidden" name="ztext_bodytext_hidden" value="{$item.bodytext|safetext}" />
      <input type="hidden" id="text_edited"  value="0" />
      
    <fieldset>
        <legend>{gt text='Create Page'}</legend>
        <div class="z-formrow">
            <label for="type_name">{gt text='Title'}</label>
            <input class="required" title="{gt text='Title required'}" type="text" id="headertext" name="formElements[headertext]" value="{$item.headertext|safetext}" />
        </div>
        <div class="z-formrow">
            <label for="phone">{gt text='Body Text'}({gt text='ID:'} ztext_bodytext)</label>
            <textarea cols="20" rows="4" id="ztext_bodytext" name="ztext_bodytext">{$item.bodytext|safetext}</textarea>
        </div>
        
        <div class="z-formrow">
            <label for="typestatus">{gt text='Active'}</label>
            <select id="pagestatus" name="formElements[active]" />
             <option value="1" {if $item.active eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                    <option value="0" {if $item.active eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
            </select>
        </div>
        <div class="z-formrow">
            <label for="typestatus">{gt text='Display on Front'}</label>
            <select id="displayonfront" name="formElements[displayonfront]" />
             <option value="1" {if $item.displayonfront eq '1'} selected='selected' {/if}>{gt text='Yes'}</option>
                    <option value="0" {if $item.displayonfront eq '0'} selected='selected' {/if}>{gt text='No'}</option>
            </select>
        </div>
        <div class="z-formrow">
            <label for="link">{gt text='Link'}</label>
            <input type="text" id="link" name="formElements[link]" value="{$item.link|safetext}" />
        </div>
        <div class="z-formrow">
            <label for="sort_order">{gt text='Sort Order'}</label>
            <input type="text" id="sort_order" name="formElements[sort_order]" value="{$item.sort_order|safetext}" />
        </div>
       
        
    </fieldset>
    <div class="z-buttons z-formbuttons">
        <span id="pageSaveBtn">
            <a href="#"  onClick="return savePage();" title="{gt text="Save"}">{img modname=core src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save"} {gt text="Save"}</a>
        </span>
        <a href="javascript:closeWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        
        <span id="image_delete">
         <button  onClick="return deleteImage('{$item.text_id}');"   type="button"  name="action" value="deleteevent" title="{gt text='Delete Image'}">
             {img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Delete Image' __title='Delete Image'}
             {gt text='Delete Image'}
        </button>
        </span>
         <span id="image_delete_span">
        </span>
        
        <span id="page_delete">
         <button  onClick="return deletePage('{$item.text_id}');"   type="button"  name="action" value="deleteevent" title="{gt text='Delete Page'}">
             {img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Delete Page' __title='Delete Page'}
             {gt text='Delete Page'}
        </button>
        </span>
        <span id="page_delete_span">
        </span>
    </div>
    </div>
</form>
      <style>
            .validation-advice{
               margin-left:196px;
                }
            </style> 