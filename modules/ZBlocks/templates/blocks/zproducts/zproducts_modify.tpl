{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name="jsgettext" value="module_zblocks_js:ZBlocks"}
{dropdownlist}
{pageaddvar name='javascript' value='modules/ZBlocks/javascript/productblock.js'} 
<h4>{gt text="Shop Name"}</h4>
<div class="z-formrow">
	<label for="shop_name">{gt text="Shop Name"}</label>
	<input id="shop_name" type="text" value="{$vars.shop_name}" name="shop_name" />
 </div>
<h4>{gt text="Site Url"}</h4>
<p class="z-informationmsg">{gt text="Please enter the minisite url eg: http://www.domain.dk/site/shoptitle"}</p>
 <div class="z-formrow">
	<label for="site_url">{gt text="Url"}</label>
	<input id="site_url" type="text" value="{$vars.site_url}" name="site_url" />
 </div>
 <h4>{gt text="Limit"}</h4>
<div class="z-formrow">
	<label for="product_limit">{gt text="No: of products to display"}</label>
	<input id="product_limit" type="text" value="{$vars.product_limit}" name="product_limit" />
 </div>
  <h4>{gt text="Category"}</h4>
  <p class="z-informationmsg">{gt text="Categories will only appear once the ministe url is entered and saved"}</p>
 {if !empty($shopCategories)}
  <div class="z-formrow">
    <label for="shop_id">{gt text="Shop category"}</label>
    <select class='mcategory' name='categories[]' id='shop_id' multiple>
    {*<option value='0'>{gt text="category"}</option>*}
    {foreach from=$shopCategories item='item'}
     <option value="{$item.prd_cat_id}"  {foreach from=$vars.$shop_id.categories item='catItem'}{if $catItem eq $item.prd_cat_id} selected="selected" {/if}{/foreach}> {$item.prd_cat_name} </option>
    {/foreach}
    </select>
</div>
  {else}
        <div class="z-formrow">
            <label for="shop_id">{gt text="Shop category"}</label>
            <div>{gt text="No categories found"}</div>
        </div> 
    {/if}
    <h4>{gt text="Manufacturer"}</h4>
  <p class="z-informationmsg">{gt text="Manufacturers will only appear once the ministe url is entered and saved"}</p>
  {if !empty($shopManufacturers)}
 <div class="z-formrow">
    <label for="shop_id">{gt text="Shop Manufacturer"}</label>
    <select class='mmanuf' name='manufacturers[]' id='shop_id' multiple>
   {* <option value='0'>{gt text="manufacturer"}</option>*}
    {foreach from=$shopManufacturers item='item'}
     <option value="{$item.manufacturer_id}"  {foreach from=$vars.$shop_id.manufacturers item='mfrItem'}{if $mfrItem eq $item.manufacturer_id} selected="selected" {/if}{/foreach}> {$item.manufacturer_name} </option>
    {/foreach}
    </select>
</div>
    {else}
        <div class="z-formrow">
            <label for="shop_id">{gt text="Shop Manufacturer"}</label>
            <div>{gt text="No manufacturer found"}</div>
        </div> 
    {/if}