
    <div class="z-admin-content-pagetitle">
           <h3>{gt text='Reactivate Demo'}</h3>
     </div>
    
    <form class="z-form" action="{modurl modname="ZSELEX" type="shop" func="reactivateDemo"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
          
            <fieldset>
               {* <div class="z-formrow">
                     <label for="typestatus" style="float:left">{gt text='Shops'}</label>
                     <div style="float:left;width:68%">{$shopIds}</div>
                     <input type="hidden" name="shopIds" value="{$shopIds}">
                
                </div>*}
                     
                <div class="z-formrow">
                     <label for="typestatus" style="float:left">{gt text='Demo Period'}</label>
                    
                     <input type="text" name="demo_period" value="">
                     <input type="hidden" name="shop_ids" value="{$shopIds}">
                
                </div>
          
             
                    
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




