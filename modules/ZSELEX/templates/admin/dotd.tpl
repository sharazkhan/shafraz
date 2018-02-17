


{pageaddvar name='javascript' value='modules/ZSELEX/javascript/validation.js'}
<script src="modules/ZSELEX/javascript/jquerycalendar/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
 

       
{shopheader}

<script>
    //jQuery.noConflict();
</script>


<script>
    //jQuery.noConflict();
</script>
 
      

   <div class="z-admin-content-pagetitle">
        {if $item.dotdId neq ''}
        <h3>{gt text='Update Deal Of The Day'}</h3>
        {else}
    	<h3>{gt text='Create Deal Of The Day'}</h3>
        {/if}
    </div>
    

	<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitdotd" shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
   
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
       
            <input type="hidden" name='formElements[elemId]' value="{$item.dotdId}" />
            <input type="hidden" name='formElements[shoptype]' value="{$shoptype}" />
            <input type="hidden" name='formElements[ishopProduct]' value="{$iproductItem.product_id}" />
            <input type="hidden" name='formElements[zshopProduct]' value="{$zenproductItem.products_id}" />
            <input type="hidden" name='formElements[shop_id]' value="{if $smarty.request.shop_id neq ''}{$smarty.request.shop_id}{else}{$item.shop_id}{/if}" />
            <input type="hidden" name='formElements[selecteddotd]' value="{$item.dotd_date}" />
            <fieldset>
                
                
                   <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[elemtName]' id='elemtName' value='{$item.dotd_name}' {*onkeyup="setSessionForDotd('elemtName' , this.value)" *} />
              
                   </div>
                
                
           
            {*
          <div class="z-formrow">
                <label for="column_name">{gt text='Column Name'}</label>
                <input type='text'  name='formElements[column_name]' id='column_name' value='{$item.column_name}'/>
               
            </div>

          <div class="z-formrow">
                <label for="columnValue">{gt text='Column Value'}</label>
                <input type='text'  name='formElements[columnValue]' id='columnValue' value='{$item.value}'/>
               
            </div>
            *}

            <div class="z-formrow">
                <label for="dotddate">{gt text='Date for DOTD'}</label>
                <input type='text'  name='formElements[dotddate]' id='dotddate' class='dotddate' value='{$item.dotd_date}' onBlur="checkDotdExist(this.value , '{if $item.dotdId neq ''}{$item.dotd_date}{/if}');" />
            </div>
          
            <div class="z-formrow">
                <label for="dotddate">{gt text='Keywords'}</label>
                <textarea  name='formElements[keywords]' id='keywords' >{$item.keywords}</textarea>
           </div>

            <div class="z-formrow" id="errordiv" style="display: none"> 
                <label for="error"><b>{gt text='Availability'}</b></label>
                   <span id="dotdMsg">{gt text='This Date is booked'} </span>
            </div>
            {if $shoptype eq 'iSHOP'}
             <div class="z-formrow">
                <label for="category">{gt text='Select Product'}</label>
                <select name='formElements[ishopProductId]' id='ishopProductId'>
                <option value='0'>select</option>
                {foreach from=$ishopProducts  item='itemi'}
                <option value="{$itemi.product_id}"  {if $itemi.product_id eq $item.value} selected="selected" {/if} > {$itemi.product_name} </option>
                {/foreach}
                </select>
           </div>
             {elseif $shoptype eq 'zSHOP'}
            <div class="z-formrow">
                <label for="category">{gt text='Select ZenCart Product'}</label>
                <select name='formElements[zshopProductId]' id='zshopProductId'>
                <option value='0'>select</option>
                {foreach from=$zShopProducts  item='itemz'}
                <option value="{$itemz.products_id}"  {if $itemz.products_id eq $item.value} selected="selected" {/if} > {$itemz.products_name} </option>
                {/foreach}
                </select>
           </div>
             {/if}
           <div class="z-formrow">
                <label for="typestatus">{gt text='Status'}</label>
                <select id="typestatus" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                    <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
             </div>
               
               <!-- <div class="z-formrow">
                      <label for="chooseproduct"></label>
                        {* <a href='#'  onclick=window.open("{$baseurl}index.php?module=ZSELEX&type=admin&func={$prodfunc}&shop_id={$smarty.request.shop_id}&redirect={$redirect}",'chooseproduct','width=1000,height=500,scrollbars=yes');>
                          {gt text='Select Product'}
                          </a>*}
                            <div class="z-buttons z-formbuttons">
                           <a class="z-btgreen" onclick=window.open("{$baseurl}index.php?module=ZSELEX&type=admin&func={$prodfunc}&shop_id={$smarty.request.shop_id}&redirect={$redirect}",'chooseproduct','width=1000,height=500,scrollbars=yes'); href="#">
                               {img modname='core' src='button_ok.png' set='icons/extrasmall' __alt='button_ok' __title='button_ok'} {gt text='Select Product'}
                           </a>
                           </div>
                   </div>  
                      {if $iproduct neq ''}    
                  <div class="z-formrow">
                      <label for="iproduct"></label>
                      <table  bgcolor="black" cellspacing="1" cellpadding="1">
                          <tr bgcolor="white">
                              <td><b>{gt text='Product Name'}</b></td>
                              <td><b>{gt text='Image'}</b></td>
                               <td><b>{gt text='Description'}</b></td>
                          </tr>
                          
                           <tr bgcolor="white">
                              <td>{$iproductItem.product_name}</td>
                              <td>  <img src="{$baseurl}zselexdata/{$ownerName}/products/thumb/{$iproductItem.prd_image}"></td>
                              <td>{$iproductItem.prd_description}</td>
                           </tr>
                       </table>
                       
                   </div> 
                  {/if}
                   {if $zenproductid neq ''}    
                  <div class="z-formrow">
                      <label for="iproduct"></label>
                      <table  bgcolor="black" cellspacing="1" cellpadding="1">
                          <tr bgcolor="white">
                              <td><b>{gt text='Product Name'}</b></td>
                              <td><b>{gt text='Image'}</b></td>
                               <td><b>{gt text='Description'}</b></td>
                          </tr>
                          
                           <tr bgcolor="white">
                              <td>{$zenproductItem.products_name}</td>
                              <td>  <img src="http://{$zenproductItem.domain}/images/{$zenproductItem.products_image}"  {if $zenproductItem.H  neq  ''} height='{$zenproductItem.H}'  width='{$zenproductItem.W}' {else} width='170px'   {/if}></td>
                              <td>{$zenproductItem.products_description}</td>
                           </tr>
                       </table>
                       
                   </div> 
                  {/if}-->
           
            <div class="z-buttons z-formbuttons">
             <div id="dotdSpan">
             <button id="zselex_button_submit"  class="z-btgreen"  type="submit" onclick="return validate_advertise();" name="action" value="1" title="{gt text='Save this DOTD'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save this DOTD' }
             {gt text='Save'}
            </button>
            </span>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewdotd' shop_id=$smarty.request.shop_id}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
            </div>
	</form>






<script type="text/javascript" language="javascript">

   jQuery(function() {
        jQuery( "#dotddate" ).datepicker({ dateFormat: "yy-mm-dd" });
       
  
    });
    
   


</script>

{adminfooter}