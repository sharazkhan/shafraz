
{adminheader}
{jsvalidator}
<div class="z-admin-content-pagetitle">
    {if $bundeleid neq ''}
    <h3>{gt text='Update Bundle'}</h3>
    {else}
    <h3>{gt text='Create Service Bundle'}</h3>
    {/if}
</div>

<form id="bundleForm" class="z-form" action="{modurl modname="ZSELEX" type="admin" func=$func id=$id}" method="post" enctype="application/x-www-form-urlencoded">
      <div>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
        <input type="hidden" name='id' value="{$bundeleid}" />
       
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
                                <input type='hidden' name="formElements[plugin_id][{$plugin.type}]" value="{$plugin.plugin_id}">
                        <td>
                            <label for="{$plugin.plugin_id}"><b>{$plugin.plugin_name}</b></label>
                        </td>
                        <td>
                            {$plugin.price}
                            <input type='hidden' name="formElements[price][{$plugin.type}]" value="{$plugin.price}">
                        </td>
                        <td>
                            {setqty origtype=$plugin.type sessionarr=$bundlesin}
                            {*<input type='text' size='2' {if $plugin.qty_based eq 0} readonly {/if}  name="formElements[qty][{$plugin.type}]" id="qty_{$plugin.type}" value="{$qtys}"  />*}
                           {if $plugin.qty_based eq 0} 
                              <input type='hidden' size='2' name="formElements[qty][{$plugin.type}]" id="qty_{$plugin.type}" value="{$qtys}"  /> 
                            {else}   
                              <input type='text' size='2' name="formElements[qty][{$plugin.type}]" id="qty_{$plugin.type}" value="{$qtys}"  /> 
                            {/if}
                            <input type='hidden' name="formElements[qty_based][{$plugin.type}]" value="{$plugin.qty_based}">
                        </td>
                        </tr>
                        {/foreach}
                    </table>
                </div> 
            </div>
            <div class="z-buttons z-formbuttons">
                <button id="zselex_button_submit"  class="z-btgreen" type="submit" onclick="" name="action" value="1" title=" {if $bundeleid eq ''}{gt text='Create Bundle'}{else}{gt text='Modify Bundle'}{/if}">
                    {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Create Bundle'}
                      {if $bundeleid eq ''}{gt text='Create Bundle'}{else}{gt text='Modify Bundle'}{/if}
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
                <input class="required" title="{gt text='Bundle name required'}" type='text'  name='formElements[name]' id='identifier' value='{$item.bundle_name}'  />
            </div>
            
            <div class="z-formrow">
                <label for="elemtName">{gt text='Identifier'}</label>
                <input type='text'  name='formElements[type]' id='identifier' value='{$item.type}'  />
            </div>

            <div class="z-formrow">
                <label for="elemtName">{gt text='Calculated Price'}</label>
                <input type='text'  name='formElements[calcprice]' id='calcprice' value={if $sum neq ''}{$sum}{else}{$item.calculated_price}{/if}  />
            </div> 

            <div class="z-formrow">
                <label for="elemtName">{gt text='Bundle Price'}</label>
                <input type='text'  name='formElements[bundleprice]' id='bundleprice' value='{$item.bundle_price}'  />
            </div>

            <div class="z-formrow">
                <label for="description">{gt text='Description'}</label>
                <textarea id="description" name="formElements[description]">{$item.bundle_description}</textarea>
            </div>
            
            {foreach item='language' from=$languages}
             
                <div class="z-formrow">
                  <label for="infomessage">{gt text="Information Message"}(<b>{$language}</b>)</label>
                       <textarea id="{$language}" name="content[{$language}][infomessage]">{$content.$language.infomessage}</textarea>
                </div>
      
          {/foreach}
             <div class="z-formrow">
               <label for="">{gt text='Bundle Type'}</label>
              <div>
                   {gt text='main'}<input type="radio" name="formElements[bundle_type]" id="main" {if $item.bundle_type eq 'main'} checked='checked' {/if} value="main"> 
                   {gt text='additional'}<input class="validate-one-required" type="radio" name="formElements[bundle_type]" id="main" {if $item.bundle_type eq 'additional'} checked='checked' {/if} value="additional"> </div>
             </div>
             
             
               <div class="z-formrow">
               <label for="is_free">{gt text='Free Bundle'}</label>
               <input type="checkbox" name="formElements[is_free]" id="is_free" {if $item.is_free eq '1'} checked='checked' {/if} value="1"> 
           </div>
           
             <div class="z-formrow">
               <label for="demo">{gt text='Available for demo'}</label>
               <input type="checkbox" name="formElements[demo]" id="demo" {if $item.demo eq '1'} checked='checked' {/if} value="1"> 
           </div>
           <div class="z-formrow">
                 <label for="demoperiod">{gt text='Demo Period'}({gt text='no: of days'})</label>
                 <input type='text'  name='formElements[demoperiod]' id='demoperiod' value='{$item.demoperiod}' />
               
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
                    {img src='button_ok.png' modname='core' set='icons/extrasmall' __alt='Save' __title='Save Bundle'}
                    {gt text='Save Bundle'}
                </button>
                <a id="zselex_button_cancel" href="{modurl modname="ZSELEX" type="admin" func='viewservicebundles'}" class="z-btred">{img modname='core' src='button_cancel.png' set='icons/extrasmall' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
            </div>
            {/if}

    </div>

</form>

  <style>
            .validation-advice{
               margin-left:252px;
                }
            </style>
                <script>
                 var valid = new Validation('bundleForm', {useTitles:true});
                </script>

{adminfooter}