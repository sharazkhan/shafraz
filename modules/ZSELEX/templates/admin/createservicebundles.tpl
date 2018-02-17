{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}

{adminheader}

<script>
    
    
    function check(serviceid,price){
    
    //alert(serviceid + "+" + price);
    var qty =   document.getElementById('qty_'+serviceid).value;
    alert(serviceid + "+" + price + "+" + qty);
    
    }

function calcprices(id,price){
//alert(price);
var cboxes = document.getElementsByName('servicess[]');
    var len = cboxes.length;
    for (var i=0; i<len; i++) {
        //alert(i + (cboxes[i].checked?' checked ':' unchecked ') + cboxes[i].value);
        if(cboxes[i].checked){
           //alert(cboxes[i].value);
           alert(cboxes[i].value+"+"+document.getElementById('qty_'+cboxes[i].value).value);
        }
        
    }
}

</script>

    <div class="z-admin-content-pagetitle">
 {if $item.plugin_id neq ''}
        <h3>{gt text='Update Identifier'}</h3>
        {else}
    	<h3>{gt text='Create Service Bundle'}</h3>
        {/if}
    </div>

	<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func=$func}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
             <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
             <input type="hidden" name='formElements[elemId]' value="{$itemdb.id}" />
             <input type="hidden" name='formElements[selectedidentifier]' value="{$itemdb.identifier}" />
            
            
            <fieldset>
              <div class="z-formrow">
              <label for="elemtName">{gt text='Services'}</label>
              <div id="ScrollCB" style="height:150px;width:350px;overflow:auto;border:1px solid #ABDDFD">  
                  <table width="308">
                      <tr>
                          <td>#</td>
                          <td><b>&nbsp;Service</b></td>
                          <td><b>Price</b></td>
                          <td><b>Qty</b></td>
                      </tr>
                 {foreach from=$plugins  item='plugin' key='key'}
                
              <tr>
                  <td>
                      <input type="checkbox" id="{$plugin.plugin_id}"  {foreach from=$bundlesin  item='bundl'}{if $bundl.servicetype eq $plugin.type}checked="checked"{/if}{/foreach}  name="formElements[services][{$plugin.type}]" value="{$plugin.type}"></td>
                      <input type='hidden' name="formElements[servicename][{$plugin.type}]" value="{$plugin.plugin_name}">
                  <td>
                    <label for="{$plugin.plugin_id}"><b>{$plugin.plugin_name}</b></label>
                  </td>
            <td>
                {$plugin.price}
                 <input type='hidden' name="formElements[price][{$plugin.type}]" value="{$plugin.price}">
            </td>
                   <td>
                       {setqty origtype=$plugin.type sessionarr=$bundlesin}
                   <input type='text' size='2' {if $plugin.qty_based eq 0} readonly {/if}  name="formElements[qty][{$plugin.type}]" id="qty_{$plugin.type}" value="{$qtys}"  />
                  </td>
              </tr>
                  {/foreach}
                  </table>
               </div> 
                  {*
                   <label for=""></label>
                  <div onClick="calcprices({$plugin.plugin_id},{$plugin.price});">
                 <b><font style="cursor:pointer" color='blue'>generate bundle</font> </b>
                  </div>
                  *}
            </div>
             {*  
            <div class="z-formrow">
                <label for="elemtName">{gt text='Calculated Price'}</label>
                <input type='text'  name='formElements[calcprice]' id='calcprice' value=''  />
            </div>
            
            <div class="z-formrow">
                <label for="elemtName">{gt text='Bundle Price'}</label>
                <input type='text'  name='formElements[bundleprice]' id='bundleprice' value=''  />
            </div>
          
             <div class="z-formrow">
                 <label for="description">{gt text='Description'}</label>
                 <textarea id="description" name="formElements[description]">{$item.description}</textarea>
             </div>
      
     
           <div class="z-formrow">
                <label for="status">{gt text='Status'}</label>
                <select id="status" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                     <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
                *}
          
          
            <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="" name="action" value="1" title="{gt text='Create Bundle'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Create Bundle' }
             {gt text='Create Bundle'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewservicebundles'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
            
            {if $bundlecount > 0}
             <div id="shwbndle" class="z-formrow">
             <label for="elemtName"><b>{gt text='Your Bundle'}</b></label>
                <table width="308">
                    <tr>
                        <td><b>Service</b></td>
                        <td><b>Price</b></td> 
                        <td><b>Qty</b></td>
                    </tr> 
                {foreach from=$bundlesin  item='bundle'}
                    <tr>
                        <td>
                            {$bundle.servicename}
                        </td>
                         <td>
                            {$bundle.price}
                        </td>
                         <td>
                            {$bundle.qty}
                        </td>
                    </tr>
                {/foreach} 
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2" align="right"><b>Calculated Price : {$sum}</b></td>
                    
                </tr>
                 </table>
              </div> 
           
            <div class="z-formrow">
                <label for="elemtName">{gt text='Name'}</label>
                <input type='text'  name='formElements[name]' id='identifier' value='{$item.bundle_name}'  />
            </div>
                    
             <div class="z-formrow">
                <label for="elemtName">{gt text='Calculated Price'}</label>
               <input type='text'  name='formElements[calcprice]' id='calcprice' value={if $sum neq ''}{$sum}{else}{$item.calculated_price}{/if}  />
            </div> 
                
            <div class="z-formrow">
                <label for="elemtName">{gt text='Bundle Price'}</label>
                <input type='text'  name='formElements[bundleprice]' id='bundleprice' value=''  />
            </div>
          
             <div class="z-formrow">
                 <label for="description">{gt text='Description'}</label>
                 <textarea id="description" name="formElements[description]">{$item.bundle_description}</textarea>
             </div>
                
             <div class="z-formrow">
                <label for="status">{gt text='Status'}</label>
                <select id="status" name="formElements[status]" />
                     <option value="1" {if $item.status eq '1'} selected='selected' {/if}>{gt text='Active'}</option>
                     <option value="0" {if $item.status eq '0'} selected='selected' {/if}>{gt text='InActive'}</option>
                </select>
            </div>
             <div class="z-buttons z-formbuttons">
             <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="" name="action" value="2" title="{gt text='Save Bundle'}">
            {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save Bundle' }
             {gt text='Save Bundle'}
            </button>
            <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewservicebundles'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
                 {/if}
            
        </div>
		
	</form>



{adminfooter}