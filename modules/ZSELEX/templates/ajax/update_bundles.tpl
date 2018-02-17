
    <div class="z-admin-content-pagetitle">
           <h3>{gt text='Update Bundles'}</h3>
     </div>
    
    <form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="updateShopBundles"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
          
            <fieldset>
                <div class="z-formrow">
                     <label for="typestatus" style="float:left">{gt text='Shops'}</label>
                     <div style="float:left;width:68%">{$shopIds}</div>
                     <input type="hidden" name="shopIds" value="{$shopIds}">
                    {*
                    <div>14,23,26,57,58,59,60,61,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79</div>
                *}
                </div>
          
                {*
            <div class="z-formrow">
                <label for="typestatus">{gt text='Bundles'}</label>
                  <select multiple id="city-combo" name="formElements[city-combo]" >
                    <option value=''>{gt text='Select'}</option>
                        {foreach from=$bundles  item='item'}
                        <option value="{$item.bundle_id}" >{$item.bundle_name|upper}</option>
                        {/foreach}
                    </select>
            </div>
                *}    
                    
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="return validate_category();" name="action" value="1" title="{gt text='Submit'}">
             {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Submit' __title='Submit'}
             {gt text='Submit'}
            </button>
            <a id="zselex_button_cancel" href="javascript:closeWindow()" shop_id=$smarty.request.shop_id}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
            </fieldset>
        </div>
	</form>
    <div>

</div>




