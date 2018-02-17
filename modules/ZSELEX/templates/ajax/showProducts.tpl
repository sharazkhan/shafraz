
<div class="z-admin-content-pagetitle">
  	<h3>{gt text='Products'}</h3>
</div>
 
        <form onsubmit="return saveCopyProduct();" class="z-form" action="{modurl modname="ZSELEX" type="admin" func="saveManufacturer"}" method="post" enctype="multipart/form-data">
    <div>
        <input type="hidden" id='shop_id' value="{$shop_id}" />
        <input type="hidden" id='curr_prd_id' value="{$curr_product_id}" />
      
        <div class="z-formrow" id="listManuf">
                <label for="product">{gt text='Products'}</label>
                <select name='formElements[product]' id='copy_prd_id'>
                 {foreach from=$products  item='item'}
                <option value="{$item.product_id}" > {$item.product_name} </option>
                {/foreach}
                </select>
               
        </div>
   
     <div class="z-buttons z-formbuttons">
       {* {button  src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="saveproduct"}*}
         <a href=""  onClick="return saveCopyProduct();" title="{gt text="Copy"}">{img modname=core src="button_ok.png" set="icons/extrasmall" __alt="Copy" __title="Copy"} {gt text="Copy"}</a>
         <a href="javascript:closeCatWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
       
    </div>
    
    </div>
</form>
