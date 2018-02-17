{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}
{ajaxheader imageviewer="true"}
{if $smarty.request.shop_id neq ''}
{shopheader}
{else}
{adminheader}  
{/if}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jqueryfordragdrop.js'}
{if $product.product_id neq ''}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dragdropimagesedit.js'}
{else}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dragdropimagescreate.js'}
 {/if}
<link href="modules/ZSELEX/style/dragdrop.css" rel="stylesheet" type="text/css" />
<div class="z-admin-content-pagetitle">
  	<h3>{gt text='Add Products'}</h3>
  </div>
 
<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitproduct"}" method="post" enctype="multipart/form-data">
    <div>
 
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" name='formElements[shop_id]' value="{$smarty.request.shop_id}" />
    <input type="hidden" name='formElements[elemId]' value="{$product.product_id}" />
    <input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />
    <input type="hidden" id="item_id" name="item_id" value="{$product.product_id}" />
    <input type="hidden" name='formElements[hiddenImage]' value="{$product.prd_image}" />
    <input type="hidden" id="existingImage" name="existingImage" value="{$product.prd_image}" />
    
     {if $product.product_id neq ''}
    <input type="hidden" id="url" name="url" value="updateProdImage" />
    {else}
    <input type="hidden" id="url" name="url" value="createProdImage" />
   {/if}
    
    <fieldset>
        <legend>{gt text='Create Product'}</legend>
          
        {if $smarty.request.shop_id eq ''}
           <div class="z-formrow">
                <label for="ishop">{gt text='Shop'}</label>
                <select name='formElements[ishop]' id='ishop'>
                {if $product.product_id eq ''}
                {foreach from=$ishop  item='item'}
                <option value="{$item.shop_id}"  {if $item.shop_id eq $product.shop_id} selected="selected" {/if} > {$item.shop_name} </option>
                {/foreach}
                {else}
                <option value="{$product.shop_id}" selected="selected">{$product.shop_name}</option>  
                {/if}
                </select>
            </div>
                
                {else}
                 <input type="hidden" name='formElements[ishop]' value="{$shop_id}" />     
          {/if}
        
        
        <div class="z-formrow">
            <label for="name">{gt text='Product Name'}</label>
            <input type="text" id="name" name="formElements[name]" value="{$product.product_name}" />
        </div>
        <div class="z-formrow">
            <label for="description">{gt text='Product Description'}</label>
            <textarea id="description" name="formElements[description]" cols="70" rows="10" />{$product.prd_description}</textarea>
        </div>
         <div class="z-formrow">
            <label for="keywords">{gt text='Keywords'}</label>
            <textarea id="keywords" name="formElements[keywords]" cols="70" rows="10" />{$product.keywords}</textarea>
        </div>
        <div class="z-formrow">
                <label for="category">{gt text='Category'}</label>
                <select name='formElements[category]' id='category'>
                <option value='0'>select</option>
                {foreach from=$category  item='item'}
                <option value="{$item.category_id}"  {if $item.category_id eq $product.category_id} selected="selected" {/if} > {$item.category_name} </option>
                {/foreach}
                </select>
        </div>
        <div class="z-formrow">
            <label for="price">{gt text='Product Price'}</label>
            <input type="text" id="price" name="formElements[price]" value="{$product.prd_price}" />
        </div>
             <div class="z-formrow">
            <label for="quantity">{gt text='Product Quantity'}</label>
            <input type="text" id="quantity" name="formElements[quantity]" value="{$product.prd_quantity}" />
        </div>
             
        <div class="z-formrow">
                <label for="productimage">{gt text='Product Image'}</label>
                <input type="file" name="files" id="productimage">
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
            <label for="status">{gt text='Product Status'}</label>
            <select id="status" name="formElements[status]" />
             <option value="1" {if $product.prd_status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
             <option value="0" {if $product.prd_status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
            </select>
        </div>
    </fieldset>
    <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
        <a href="{modurl modname="ZSELEX" type="admin" func='viewproducts' shop_id=$smarty.request.shop_id}" title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
    </div>
    
      
    </div>
</form>
{adminfooter}