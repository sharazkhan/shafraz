
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/basket.js'}
<script>

 function confirmation(){
        if(confirm(Zikula.__("Activate demo period for this service?"))==true)
            return true;
        else
            return false;
    }

</script>
<div class="Admin_Left left">
    <p class="pagetree">{gt text='You are here'}: {gt text='Administration'} > <span class="orange_text">{gt text='Services'}</span></p>
    <h2 class="ServiceHead_h2">{$bundle.bundle_name} </h2>
    {assign var="info" value=$bundle.content|unserialize}   
            
    <p> {$info.$thislang.infomessage}.  </p>
    <div class="ServiceSectionContainer">
    <div class="Section FullWidth">
            <div class="Settings_Image_Section Service_Box">                            		
                <div class="Setting_section FullWidth">
                    <div class="Setting_section_Orange topCurve FullWidth">
                        <h3 class="BundleFolded_h3">{$bundle.bundle_name} <span class="right">{gt text='Price'}: {displayprice amount=$bundle.bundle_price|safetext} {gt text='DKK/mdr'}</span></h3>
                </div>
                    <table class="ServiceTable BundleFolded_table">
                        <!-- Add Rows From Here -->    
                         {foreach item='item' key=index from=$bundle_items}
                        <tr class="HeadingTD PadL20"><td colspan="2" class="TextLeft" align="left">
                                {$item.service_name} {if $item.qty_based}({$item.qty}){/if}
                            </td></tr>
                        {assign var="iteminfo" value=$item.content|unserialize}   
                        <tr><td colspan="2" ><p class="FullWidth TextLeft">{$iteminfo.$thislang.infomessage}</p> </td></tr>
                        {/foreach}
                        <!-- End of  Rows Section -->
                        
                     <tr class="BottonRow">
                          {if $bundle.cantbuy < 1}
                         <td style="width:41%" class="TextLeft">
                              {if $bundle.demo}
                                  {if !$bundle.bundle_exist}
                                       {if $bundle.bundle_type neq 'additional'}
                                           {assign var="demo_period" value=$bundle.demoperiod}
                                   <a href="javascript:document.democart{$bundle.bundle_id}.submit()" onclick="return confirmation()">
                                  <button class="gray_button" type="button">
                                      {gt text='Try demo'}<span class="Right_Arrow"></span>
                                  </button>
                                  </a>
                                       {/if}
                                       
                                  {else}  
                                       {if $bundle.button_show} <!-- button show -->
                                            {if $bundle.existing_bundle.running}
                                                   {if $bundle.existing_bundle.time_left > 0}
                                                        {assign var="demo_period" value=$bundle.existing_bundle.time_left}
                                                <a href="javascript:document.democart{$bundle.bundle_id}.submit()" onclick="return confirmation()">
                                                <button class="gray_button" type="button">
                                                    {gt text='Try demo for'} {$demo_period}  {gt text='days'}<span class="Right_Arrow"></span>
                                                </button>
                                                </a>
                                                    {/if}
                                            {/if}
                                        {/if} <!-- button show -->
                                 {/if}
                                
                                  {*<form id="democart{$bundle.bundle_id}" name="democart{$bundle.bundle_id}" action="{modurl  modname='ZSELEX' type='admin' func='addToDemo'}"  method="post">*}
                                  <form id="democart{$bundle.bundle_id}" name="democart{$bundle.bundle_id}" action="{modurl  modname='ZSELEX' type='admin' func='addService'}"  method="post">   
                                        <input type="hidden" id="cart_quantitydemo{$bundle.plugin_id}" name="cart_quantity" value="1" />
                                        <input type="hidden" name="qty_based" value="{if $bundle.qty_based eq 0}0{else}1{/if}" /> 
                                        <input type="hidden" name="serviceId" value="{$bundle.plugin_id}" /> 
                                        <input type='hidden' name='servicetype' value="{$bundle.type}" >
                                        <input type='hidden' name='serviceprice' value="{$bundle.bundle_price}" >
                                        <input type='hidden' name='demoperiod' value="{if $bundle.demoperiod < 1}1{else}{$demo_period}{/if}">
                                        <input type='hidden' name='bundle' value="1" >
                                        <input type='hidden' name='bundle_id' value="{$bundle.bundle_id}" >
                                        <input type='hidden' name='top_bundle' value="1" >
                                        <input type="hidden" name="shop_id" value="{$smarty.request.shop_id}" /> 
                                        <input type="hidden" name="service_status" value="1" /> 
                                        <input type="hidden" name="service_depended" value="{$bundle.service_depended}" />
                                        <input type="hidden" name="source" value="quickbuy" />
                                  </form>
                              {/if}
                              
                         </td>
                            <td style="width:65%; text-align:right">
                                {*quantity: <input type="text"  value="1" />*}
                                {if $bundle.button_show} 
                                     {if $bundle.existing_bundle.bundle_status eq 1 AND !$bundle.existing_bundle.running}
                                  <a href="javascript:document.servicecart{$bundle.bundle_id}.submit()">
                                <button class="Orange_button ButtonPad" type="button"><span class="ButtomText">{gt text='Upgrade'}</span>
                                    <span class="Right_Arrow"></span>
                                </button>
                                   </a>
                                    {/if}
                                 {/if}   
                                 <form id="servicecart{$bundle.bundle_id}" name="servicecart{$bundle.bundle_id}" action="{modurl  modname='ZSELEX' type='admin' func='serviceCart'}"  method="post">
                                        <input type="hidden" id="cart_quantity{$bundle.plugin_id}" name="cart_quantity" value="1" />
                                        <input type="hidden" name="qty_based" value="{if $bundle.qty_based eq 0}0{else}1{/if}" /> 
                                        <input type="hidden" name="serviceId" value="{$bundle.plugin_id}" /> 
                                        <input type='hidden' name='servicetype' value="{$bundle.type}" >
                                        <input type='hidden' name='serviceprice' value="{$bundle.bundle_price}" >
                                        <input type='hidden' name='demoperiod' value="{if $bundle.demoperiod < 1}1{else}{$buybundle.demoperiod}{/if}" >
                                        <input type='hidden' name='bundle' value="1" >
                                        <input type='hidden' name='bundle_id' value="{$bundle.bundle_id}" >
                                        <input type='hidden' name='top_bundle' value="1" >
                                        <input type="hidden" name="shop_id" value="{$smarty.request.shop_id}" /> 
                                        <input type="hidden" name="service_status" value="2" /> 
                                        <input type="hidden" name="service_depended" value="{$bundle.service_depended}" />
                                        <input type="hidden" name="source" value="quickbuy" />
                                  </form>
                                 
                            </td>
                            {else}
                            <td colspan="2">
                                 <b>{$bundle.msg}</b>
                            </td>
                             {/if}   
                        </tr>
                       {if $bundle.bundlebuy.depended_services} 
                        <tr class="BottonRow">
                                 <td>
                                     <table>
                                         <th>{gt text='Depended Services'} :</th>
                                    {foreach from=$bundle.bundlebuy.depended_services item='buyitem' key='key'}

                                                 <tr>
                                                    <td align="left">{$key+1}</td>
                                                    <td align="left">{$buyitem.name}</td>
                                                    <td align="left">{$buyitem.canbuystatus}</td>
                                                 </tr>

                                     {/foreach} 
                                      </table>    
                           </td>
                        </tr>
                        {/if}
                    </table>

                </div>                                    
            </div>
        </div>
  </div>
                 {if $bundle.service_exist AND $bundle.bundle_type neq 'additional'}
                  <div  align="center">{gt text='You have this bundle'}</div>
                {/if} 
                {if $bundle.service_exist AND $bundle.bundle_type eq 'additional'}
                  <div  align="center">{gt text='You have'} {$bundle.service_exist_details.quantity} {gt text='of this bundle'}</div>
                {/if} 

</div>
<div class="Admin_Right left">
      {blockposition name='yourservices'}
</div>



