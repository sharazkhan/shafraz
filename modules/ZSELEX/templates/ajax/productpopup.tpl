

{*<div class="z-admin-content-pagetitle">
    <h3 style="float:left">{gt text='Edit Products Information'}</h3> <span style="float:right;"><button  type="button" onClick="copyProduct('{$product.product_id}')"  class="ProductPageBtn">{gt text='copy a product'}</button></span>
</div>*}
<span style="float:right;"><a href=""  onClick="return copyProduct('{$product.product_id}')"><b>{gt text='Copy details from other product'}</b></a></span>
<ul id="tabs_example_eq" style="display:block">
<li class="tab"><a href="#eqone">{gt text='Product Details'}</a></li>
<li class="tab"><a href="#eqtwo">{gt text='Product Options'}</a></li>
<li class="tab"><a href="#eqthree">{gt text='Settings'}</a></li>
<li class="tab"><a href="#eqfour">{gt text='Quantity based discount'}</a></li>
</ul>

<form id="proddelete_popup_form" action="{modurl modname="ZSELEX" type="admin" func="saveProducts"}" method="post" enctype="multipart/form-data">
 {*<input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />*}
 <input type="hidden" name='formElements[startnum]' value="{$smarty.request.startnum}" />
 <input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />
 <input type="hidden"  name='formElements[product_id]' value="{$product.product_id}" />
 <input type="hidden" name='formElements[shop_id]' value="{$smarty.request.shop_id}" />
 <input type="hidden" id="src" name="src" value="{if isset($smarty.request.src)}{$smarty.request.src}{else}'normal'{/if}" />
 <input type="hidden" id="prdc_del" name="action" value='deleteproduct'>
</form>


<form class="z-form" id="prod_popup_form" action="{modurl modname="ZSELEX" type="admin" func="saveProducts"}" method="post" enctype="multipart/form-data">
   <div id="eqone"  style="width:500px; height:550px; overflow:auto">
       {if $error_msg neq ''}
 <div style="color:red">{$error_msg}</div>
 {/if}
  
    <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
    <input type="hidden" name='formElements[shop_id]' value="{$smarty.request.shop_id}" />
    <input type="hidden" name='formElements[source]' value="popup" />
    <input type="hidden" name='formElements[startnum]' value="{$smarty.request.startnum}" />
    <input type="hidden" id="product_id" name='formElements[elemId]' value="{$product.product_id}" />
    <input type="hidden" id="urltitle" name='formElements[urltitle]' value="{$product.urltitle}" />
    <input type="hidden" id="oldname" name='formElements[oldname]' value="{$product.product_name}" />
    <input type="hidden" id="oldurltitle" name='formElements[oldurltitle]' value="{$product.urltitle}" />
    <input type="hidden"  name='formElements[product_id]' value="{$product.product_id}" />
    <input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />
    <input type="hidden" id="item_id" name="item_id" value="{$product.product_id}" />
    <input type="hidden" name='formElements[hiddenImage]' value="{$product.prd_image}" />
    <input type="hidden" id="existingImage" name="existingImage" value="{$product.prd_image}" />
    <input type="hidden" id="src" name="src" value="{if isset($smarty.request.src)}{$smarty.request.src}{else}'normal'{/if}" />
    <input type="hidden" id="testing"  value="" />
    <input type="hidden" id="qty_discounts_count"  value="{$qty_discounts_count}" />
 
        <div class="z-formrow">
            <label for="name">{gt text='Name'}</label>
            <input type="text" id="name" name="formElements[name]" value="{$product.product_name|cleantext}" />
        </div>
        <div class="z-formrow">
            <label for="description">{gt text='Description'}</label>
            <textarea id="description" name="formElements[description]" cols="30" rows="2" />{$product.prd_description|cleantext}</textarea>
        </div>
        
        {*<div class="z-formrow" id="listCat">
                <label for="category">{gt text='Category'}</label>
                <select name='formElements[category]' id='category'>
                <option value='0'>{gt text='select category'}</option>
                {foreach from=$category  item='item'}
                <option value="{$item.prd_cat_id}"  {if $item.prd_cat_id eq $product.category_id} selected="selected" {/if} > {$item.prd_cat_name} </option>
                {/foreach}
                </select>
               
        </div>*}
        
         <div class="z-formrow" id="listCat">
                <label for="prod_cats">{gt text='Categories'}</label>
                <select name='formElements[prod_cats][]' class="mcategory" id='s1a' multiple="multiple">
               
                {foreach from=$category  item='item'}
                <option value="{$item.prd_cat_id}" {foreach from=$product.categories  item='cat'} {if $item.prd_cat_id eq $cat.category_id} selected="selected" {/if}{/foreach}> {$item.prd_cat_name} </option>
                {/foreach}
                </select>
               
        </div>
        <div class="z-formrow">
             <label for="cat"></label>
            
         <button type="button" onClick="editCategory('new')"  class="ProductPageBtn">{gt text='create new category'}</button>
        </div>
      
        <div class="z-formrow" id="listManuf">
                <label for="category">{gt text='Manufacturer'}</label>
                <select name='formElements[manufacturer]' id='category'>
                <option value='0'>{gt text='select manufacturer'}</option>
                {foreach from=$manufacturers  item='item'}
                <option value="{$item.manufacturer_id}"  {if $item.manufacturer_id eq $product.manufacturer_id} selected="selected" {/if} > {$item.manufacturer_name} </option>
                {/foreach}
                </select>
               
        </div>
        <div class="z-formrow">
             <label for="cat"></label>
            {* <span style="cursor:pointer;color:blue" onClick="editManufacturer();">{gt text='create new manufacturer'}</span>*}
            <button type="button" onClick="editManufacturer()"  class="ProductPageBtn">{gt text='create new manufacturer'}</button>
        </div>
        <div class="z-formrow">
            <label for="price">{gt text='Price'}</label>
            <input onfocus="setDiscountPrice(this.value , document.getElementById('discount').value)" onblur="setDiscountPrice(this.value , document.getElementById('discount').value)" onkeyup="setDiscountPrice(this.value , document.getElementById('discount').value)" type="text" id="price" name="formElements[original_price]" value="{$product.original_price_new}" />
        </div>
        
         <div class="z-formrow">
            <label for="discount">{gt text='Discount'}</label>
            <input {*onkeyup="setDiscountPrice(document.getElementById('price').value,this.value)" *} autocomplete="off" type="text" id="discount" name="formElements[discount]" value="{$product.discount}" />
           
        </div>
            {*
        <div class="z-formrow" id="discount_val">
             <label for="discount">{gt text='Selling Price'}</label>
             <input type="text" readonly id="prd_price" name="formElements[prd_price]" value="{$product.prd_price_new}" />
        </div>
        *}
        <div class="z-formrow" >
            <label for="quantity">{gt text='Quantity'}</label>
            <input  {if $optionExistForProduct}readonly style="background-color:#C0C0C0"{/if} type="text" id="quantity" name="formElements[quantity]" value="{$product.prd_quantity}" />
        </div>
         <div class="z-formrow">
            <label for="quantity">{gt text='Shipping Charge'}</label>
            <input type="text" id="quantity" name="formElements[shipping]" value="{$product.shipping_price}" />
        
            <div align="right">
                <font color="grey">
                <i>{gt text='Note'} : {gt text='Product shipping overrules Standard Shipping settings'}</i>
                </font>
                <a href="{modurl modname="ZSELEX" type="admin" func="shopsettings" shop_id=$smarty.request.shop_id}">{gt text='Shop Settings'}</a>
            </div>
         </div>
        <div class="z-formrow" >
            <label for="advertise">{gt text='Advertise'}</label>
            <input type="checkbox" id="advertise" name="formElements[advertise]" value="1" {if $product.advertise} checked {/if}/>
        </div>
      
        <div class="z-formrow">
            <label for="status">{gt text='Status'}</label>
            <select id="status" name="formElements[status]" />
             <option value="1" {if $product.prd_status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
             <option value="0" {if $product.prd_status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
            </select>
        </div>
   
     <div class="z-buttons z-formbuttons">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="saveproduct"}
        {if $smarty.request.src eq 'detail'} 
         <a href="{modurl modname="ZSELEX" type="user" func="productview" id=$product.product_id}"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
         {else}
         <a href="javascript:closeWindow()"  title="{gt text="Cancel"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        {/if}
        {* {button src="14_layer_deletelayer.png" set="icons/extrasmall" __alt="Delete Product" __title="Delete Product" __text="Delete Product" __name="action" __value="deleteproduct"} *}
         <button onClick="return deleteProduct();" id="product_delete"  type="button"  name="action" value="deleteproduct" title="{gt text='Delete Product'}">
             {img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt='Delete Product' __title='Delete Product'}
             {gt text='Delete Product'}
         </button>
         
    </div>
    
    </div>
     
   <!------  TAB TWO -->      
    <div id="eqtwo" style="display:none;">
        {*<div class="z-formrow">
        <label for="" id="addC"><b>{gt text='Add Options'}</b></label>
        </div>*}


     <div class="prd_option">
         <div id="optionToClone" style="display:block">
         
         <div class="z-formrow" class="optionDiv">
                <label for="category">{gt text='Options'}</label>
                <select {*name='formElements[option][main][]'*} class='optionList'>
                <option value='0'>{gt text='select option'}</option>
                {foreach from=$product_options  item='item'}
                <option value="{$item.option_id}"> {$item.option_name} </option>
                {/foreach}
                </select>
                <span class='busyOpt' style="margin-left:140px"></span>
                <div class="innerC" style="margin-left:146px;max-height:500px;overflow:auto">
                     {foreach from=$product.options  item='mitem' key='mkey'}
                         {if $mitem.parent_option_id < 1}
                         <div class="innerCsub">
                             {*<input type="hidden" name="formElements[option][main][]" value='{$mitem.option_id}'>*}
                             <div><b>{$mitem.option_name}</b></div>
                    <table  class="optionTable" cellspacing='1' cellpadding='1'>
                      
                       <th bgcolor='white'>{gt text='values'}</th>
                       <th bgcolor='white'>{gt text='price'}</th>
                       <th bgcolor='white'>{gt text='qty'}</th>
                       {*<th bgcolor='white'>{gt text='sort order'}</th>*}
                         {assign var="mitem_vals" value=$mitem.option_value|unserialize}
                         {assign var="sitem_vals" value=$mitem.option_values|unserialize} 
                         {foreach from=$mitem.main_option_values item='main_item'}
                        <tr bgcolor='white'>
                            <input type="hidden" name="formElements[option][{$mkey}][oldId]" value='{$mitem.product_to_options_id}'>
                            <input type="hidden" name="formElements[option][{$mkey}][type]" value='old'>
                            <input  id="selOptionId" class="selOptionId" type="hidden" name="formElements[option][{$mkey}][option_id]" value='{$mitem.option_id}'>
                            <input type="hidden" name="formElements[option][{$mkey}][hval][]" value='{$main_item.option_value_id}'>
                            {*<td><input type='checkbox' name='formElements[option][{$mkey}][val][]' value={$main_item.option_value_id}  {foreach from=$mitem.values item='item2'}{if $item2.option_value_id eq $main_item.option_value_id}checked{/if}{/foreach}></td>*}
                            <td align="center">{$main_item.option_value}</td>
                            <td>
                                  <input type='text' {foreach from=$mitem.values item='item3' key='k3'}{if $item3.option_value_id eq $main_item.option_value_id}value='{$item3.price}'{/if}{/foreach}  name='formElements[option][{$mkey}][price][{$main_item.option_value_id}]' size='3' autocomplete='off'>
                            </td>
                            <td><input type='text' {foreach from=$mitem.values item='item3' key='k3'}{if $item3.option_value_id eq $main_item.option_value_id}value='{$item3.qty}'{/if}{/foreach}  name='formElements[option][{$mkey}][qty][{$main_item.option_value_id}]' size='3' autocomplete='off'></td>
                            {*<td><input type='text' {foreach from=$mitem.values item='item3' key='k3'}{if $item3.option_value_id eq $main_item.option_value_id}value='{$item3.sort_order}'{/if}{/foreach}  name='formElements[option][{$mkey}][sort_order][{$main_item.option_value_id}]' size='3' autocomplete='off'></td>*}
                        </tr>
                         {/foreach} 
                     </table>
                         <span><a class="remove_option" href="#">  <b>{gt text='remove'}</b></a></span>
                         <br>
                         </div>
                         {else}
                        <div class="innerCsub" style="max-width:600px; overflow:auto">
                             <div><b>{$mitem.option_name}</b></div>
                      <table   cellspacing='1' cellpadding='1' class="optionTable">
                      {* <th bgcolor='white'>#</th>*}
                       <th bgcolor='white'>{gt text='values'}</th>
                       
                       {getParentProductOptions option_id=$mitem.parent_option_id}
                       {foreach from=$parent_product_options item='parent_item'}
                       <th bgcolor='white'>
                           {$parent_item.option_name} :
                           <br>
                          {$parent_item.option_value}
                       </th>
                      
                       {/foreach}
                      
                         {foreach from=$mitem.main_option_values item='main_item'}
                        <tr bgcolor='white'>
                            <input type="hidden" name="formElements[option][{$mkey}][linked]" value='1'>
                            <input type="hidden" name="formElements[option][{$mkey}][type]" value='old'>
                            <input type="hidden" name="formElements[option][{$mkey}][oldId]" value='{$mitem.product_to_options_id}'>
                            <input id="selOptionId" class="selOptionId" type="hidden" name="formElements[option][{$mkey}][option_id]" value='{$mitem.option_id}'>
                            <input type="hidden" name="formElements[option][{$mkey}][parent_option_id]" value='{$mitem.parent_option_id}'>
                            <input type="hidden" name="formElements[option][{$mkey}][hval][]" value='{$main_item.option_value_id}'>
                           {* <td>
                               
                              <input type='checkbox' name='formElements[option][{$mkey}][val][]' value={$main_item.option_value_id}  {foreach from=$mitem.values item='item2'}{if $main_item.option_value_id eq $item2.option_value_id}checked="checked"{/if}{/foreach}>
                           </td>*}
                            <td align="center">{$main_item.option_value}</td>
                             {foreach from=$parent_product_options item='parent_item'}
                                <td>
                                 <table>
                                    <tr>
                                    
                                     <td> 
                                        {gt text='price'} : <input type='text' name='formElements[option][{$mkey}][price][{$main_item.option_value_id}][{$parent_item.option_value_id}]'  size='3' autocomplete='off' {foreach from=$mitem.values item='item2'}{if $parent_item.option_value_id eq $item2.parent_option_value_id AND $main_item.option_value_id eq $item2.option_value_id}value="{$item2.price}"{/if}{/foreach}>
                                     </td>
                                     <td> 
                                        {gt text='qty'} : <input type='text' name='formElements[option][{$mkey}][qty][{$main_item.option_value_id}][{$parent_item.option_value_id}]' size='3' autocomplete='off' {foreach from=$mitem.values item='item2'}{if $parent_item.option_value_id eq $item2.parent_option_value_id AND $main_item.option_value_id eq $item2.option_value_id}value="{$item2.qty}"{/if}{/foreach}>
                                     </td>
                                     </tr>
                                 </table>
                                </td>
                             {/foreach}
                           
                        </tr>
                         {/foreach} 
                     </table>
                         <span><a class="remove_option" href="#">  <b>{gt text='remove'}</b></a></span>
                         <br>
                        </div>
                         {/if}    
                         
                      {/foreach} 
                 </div>
                {*<a style="margin-left:426px" class="remove_option" href="#">  <b>{gt text='remove'}</b></a>*}
                
        </div>
          </div>
      </div>
       <div style="margin-left:146px">
          <i style="color:grey"><b> {gt text='Note'} </b>: {gt text='Place + or - in front of option price to add or subtract from product price.'}
           <br>
           {gt text='Value without + or - will be new product price for that option.'}
           </i>
       </div>
         {*
          <div class="z-buttons z-formbuttons" style="display:block">
             
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="saveproduct"}
        
         <a href="javascript:closeWindow()">
     
          <img width="16" height="16" title="Back" alt="Back" src="{$themepath}/images/icon_cp_backtoshoplist.png">
          {gt text="Back"}
       </a>
           </div>
       *}
  </div>
  
  <div id="eqthree" style="display:none;">
       <div class="z-formrow">
            <label for="keywords">{gt text='Keywords'}</label>
            <textarea id="keywords" name="formElements[keywords]" cols="30" rows="2" />{$product.keywords|cleantext}</textarea>
        </div>
        
        <div class="z-formrow">
            <label for="no_vat">{gt text='No Vat'}</label>
            <input type="checkbox" id="no_vat" name="formElements[no_vat]" {if $product.no_vat eq 1}checked{/if} value="1"/>
        </div>
        
        <div class="z-formrow">
            <label for="max_discount">{gt text='Maximum discount'}</label>
            <input type="text" id="max_discount" name="formElements[max_discount]"  value="{$product.max_discount|cleantext}"/>
        </div>
        
          <div class="z-formrow">
            <label for="no_vat">{gt text='No Delivery'}</label>
            <input type="checkbox" id="no_vat" name="formElements[no_delivery]" {if $product.no_delivery eq 1}checked{/if} value="1"/>
        </div>
     
           <fieldset>
        <legend>{gt text='Question'}</legend>
       <div class="z-formrow">
            <label for="quantity">{gt text='Enable'}</label>
            <input type="checkbox" id="enable_question" name="formElements[enable_question]" {if $product.enable_question eq 1}checked{/if} value="1"/>
        </div>
         <div class="z-formrow">
            <label for="quantity">{gt text='Question'}</label>
            <input type="text" id="prd_question" name="formElements[prd_question]" value="{$product.prd_question}" />
        </div>
         <div class="z-formrow">
            <label for="quantity">{gt text='Mandatory'}?</label>
            <input type="checkbox" id="validate_question" name="formElements[validate_question]" {if $product.validate_question eq 1}checked{/if} value="1"/>
        </div>
        </fieldset>
       
  </div>
        <div id="eqfour" style="display:none;">
            
            <div class="z-formrow" style="width:100%">
            <label for="addQty" id="addQty"><b>{gt text='Add Qty'}</b></label>
            <div>
               <table class="qtyTd" >
                   <th>{gt text='Quantity'}</th>
                   <th>{gt text='Discount'}</th>
                   <th>{gt text='Start'}</th>
                   <th>{gt text='End'}</th>
                   <th></th>
                     {foreach from=$qty_discounts item='ditem' key='key'}
                     <tr>
                         <td><input autocomplete="off" type="text" name=formElements[qtydisount][qty][] value="{$ditem.quantity}"></td>
                         <td><input autocomplete="off" type="text" name=formElements[qtydisount][discount][] value="{$ditem.discount}"></td>
                         <td><input class="qty_date start_date{$key+1}" date_attr={$key+1} autocomplete="off" type="text" name=formElements[qtydisount][startdate][] value="{$ditem.start_date}"></td>
                         <td><input class="qty_date2 end_date{$key+1}" date_attr={$key+1} autocomplete="off" type="text" name=formElements[qtydisount][enddate][] value="{$ditem.end_date}"></td>
                         <td><a href="#" class="remove_this2">{gt text='remove'}</a></td>
                     </tr>
                     {/foreach}
                </table>
            </div>
            
        </div>
        </div>
</form>
        
         
