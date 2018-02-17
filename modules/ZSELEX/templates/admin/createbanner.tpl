
{ajaxheader imageviewer="true"}
{if $smarty.request.shop_id neq ''}
{shopheader}
{else}
{adminheader}  
{/if}



<div class="z-admin-content-pagetitle">
  	<h3>{gt text='Upload Banner'}</h3>
  </div>
 
<form class="z-form" action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <div>
 
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" name='formElements[shop_id]' value="{$smarty.request.shop_id}" />
    <input type="hidden" name='formElements[elemId]' value="{$product.product_id}" />
    <input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />
  
    
    <fieldset>
        <legend>{gt text='Create Banner'}</legend>
     
        <div class="z-formrow">
                <label for="bannerimage">{gt text='Banner Image'}</label>
                <input type="file" name="bannerfile" id="bannerimage">
        </div>
           {if $product.shop_id neq ''}
              {assign var="image" value="zselexdata/`$ownerName`/products/thumb/`$product.prd_image`"}
             {if file_exists($image)}
       <div class="z-formrow">
                   <label for="">  </label>
                  <div>
                    <a id="{$item.file_id}" rel="imageviewer[gallery]" title="{$product.prd_image}" href="{$baseurl}zselexdata/{$ownerName}/products/fullsize/{$product.prd_image}">
                    <img src="{$baseurl}zselexdata/{$ownerName}/products/thumb/{$product.prd_image}"></a>
                </div>
       </div>
                {/if}
              {/if}
              
        
        <div class="z-formrow">
            <label for="status">{gt text='Status'}</label>
            <select id="status" name="formElements[status]" />
             <option value="1" {if $product.prd_status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
             <option value="0" {if $product.prd_status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
            </select>
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewminisitebanner' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    
      
    </div>
</form>
{adminfooter}