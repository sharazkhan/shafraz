{pageaddvar name='javascript' value='modules/ZSELEX/javascript/basket.js'}

<script>
    function confirmation(){
        if(confirm(Zikula.__("Activate demo period for this service?"))==true)
            return true;
        else
            return false;
    }
    
     jQuery(document).ready(function(){
        jQuery(".HeadingTD").click(function(){
             jQuery("#HidenRow"+this.id).slideToggle("fast");
             if(jQuery("#HidenRowDep"+this.id)){
             jQuery("#HidenRowDep"+this.id).slideToggle("fast");
             }
            
        });
    });
</script>


   
    <p class="pagetree">{gt text='You are here'}: {gt text='Administration'} > <span class="orange_text">{gt text='Buy Services'}</span></p>
    <h2 class="ServiceHead_h2">{gt text='Buying Services'}</h2>
    <p>{gt text="Here you have the option of purchasing services for your store. We have put together some packages of popular services that will cover many needs. But you also have the option to choose and combine the services of your shopping needs."}</p>
    <div class="ServiceSectionContainer">
       {foreach item='bundle' key=index from=$bundles}
            <div class="SectionRow">
                 {foreach item='bundle'  from=$bundle}
                     <div class="Section">
                         <div class="Settings_Image_Section Service_Box">                            		
                            <div class="Setting_section">
                                <div class="Setting_section_Orange topCurve">
                                    <a href="{modurl modname="ZSELEX" type="admin" func="buybundle" shop_id=$smarty.request.shop_id bundle_id=$bundle.bundle_id}">{$bundle.plugin_name}  <span class="ArrowShade"></span> </a>
                                </div>
                                 <a href="{modurl modname="ZSELEX" type="admin" func="buybundle" shop_id=$smarty.request.shop_id bundle_id=$bundle.bundle_id}">
                                <ul class="ServiceSecUl">
                                     {foreach item='bundleitem'  from=$bundle.bundleitems}
                                    <li><span>{$bundleitem.service_name} ({$bundleitem.qty})</span></li>
                                     {/foreach}
                                </ul>
                                </a>
                                <h3 class="ServiceSecH3">{gt text='Price'}: {displayprice amount=$bundle.price|safetext} {gt text='DKK/mdr'}</h3>
                            </div>                                    
                        </div> 
                    </div>
                  {/foreach}
             </div> 
         {/foreach}
     </div>
    <h2 class="Backend_h2">{gt text='All services'}: <span class="ServiceHeadSpan">{gt text='Price per month'}</span></h2>
    {foreach item='service' key=index from=$services}
    <div class="CurvedBorder">
        <div class="SeviceHover">
            <table class="ServiceTable">
                <tr class="HeadingTD" id="{$service.plugin_id}"><td>{$service.plugin_name}</td><td class="TextRight Orange" align="right">{displayprice amount=$service.price|safetext} {gt text='DKK'}</td></tr>
                <tr {*class="HidenRow"*} style="display:none;" id="HidenRow{$service.plugin_id}">
                    <td colspan="2" style="padding:0px;">
                        <table id="ServicePopOverTable" class="FullWidth">
                            <tr><td colspan="2" ><p>{lingualtext content=$service.content}</p> </td></tr>
                            <tr class="BottonRow">
                                {if $service.cantbuy < 1}
                                <td style="width:35%">
                                      {if $service.demo_status eq 1}
                                     <a href="javascript:document.democart{$service.plugin_id}.submit()" onclick="return confirmation()"><button class="gray_button" type="button">{gt text='Try Demo'}<span class="Right_Arrow"></span></button></a>
                                     <form id="democart{$service.plugin_id}" name="democart{$service.plugin_id}" action="{modurl  modname='ZSELEX' type='admin' func='demoCart'}"  method="post">
                                        <input type="hidden" id="cart_quantitydemo{$service.plugin_id}" name="cart_quantity" value="1" />
                                        <input type="hidden" name="qty_based" value="{if $service.qty_based eq 0}0{else}1{/if}" /> 
                                        <input type="hidden" name="serviceId" value="{$service.plugin_id}" /> 
                                        <input type='hidden' name='servicetype' value="{$service.type}" >
                                        <input type='hidden' name='serviceprice' value="{$service.price}" >
                                        <input type='hidden' name='demoperiod' value="{if $service.demoperiod < 1}1{else}{$service.demoperiod}{/if}" >
                                        <input type='hidden' name='bundle' value="{$service.bundle}" >
                                        <input type='hidden' name='bundle_id' value="{$service.bundle_id}" >
                                        <input type='hidden' name='top_bundle' value="{$service.top_bundle}" >
                                        <input type="hidden" name="shop_id" value="{$smarty.request.shop_id}" /> 
                                        <input type="hidden" name="service_depended" value="{$service.service_depended}" />
                                        <input type="hidden" name="source" value="quickbuy" />
                                     </form>
                                     {/if}
                                </td>
                                <td style="width:65%; text-align:right">
                                    {gt text='quantity'}: <input  {if $service.qty_based eq 0} disabled="disabled" {/if}  type="text" id="number" value="1"  onkeyup="addQuantity({$service.plugin_id} , this.value);getServiceCount(this.value , {$service.price} , {$service.plugin_id});"/> 
                                     <a href="javascript:document.servicecart{$service.plugin_id}.submit()">
                                    <button class="Orange_button ButtonPad" type="button"><span class="ButtomText">{gt text='Buy service'}</span>
                                        <span class="Right_Arrow"></span>
                                    </button>
                                    </a>
                                     <form id="servicecart{$service.plugin_id}" name="servicecart{$service.plugin_id}" action="{modurl  modname='ZSELEX' type='admin' func='serviceCart'}"  method="post"  target="_blank">
                                        <input type="hidden" id="cart_quantity{$service.plugin_id}" name="cart_quantity" value="1" />
                                        <input type="hidden" name="qty_based" value="{if $service.qty_based eq 0}0{else}1{/if}" /> 
                                        <input type="hidden" name="serviceId" value="{$service.plugin_id}" /> 
                                        <input type='hidden' name='servicetype' value="{$service.type}" >
                                        <input type='hidden' name='serviceprice' value="{$service.price}" >
                                        <input type='hidden' name='bundle' value="{$service.bundle}" >
                                        <input type='hidden' name='bundle_id' value="{$service.bundle_id}" >
                                        <input type='hidden' name='top_bundle' value="{$service.top_bundle}" >
                                        <input type="hidden" name="shop_id" value="{$smarty.request.shop_id}" />
                                        <input type="hidden" name="isDepended" value="{$service.service_depended}" />
                                        <input type="hidden" name="source" value="quickbuy" />
                                     </form>
                                </td>
                                {else}
                                    <td colspan="2">
                                     {$service.msg}
                                    </td>
                                {/if}
                            </tr>
                        </table>
                    </td>
                </tr>
                {if $service.service_depended}
                 <tr style="display:none;" id="HidenRowDep{$service.plugin_id}">
                     <td>
                         <table>
                             <th>{gt text='Depended Services'} :</th>
                        {foreach from=$service.buy.depended_services item='buyitem' key='key'}
                                
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
       
    {/foreach}
    
         <input type="hidden" name="hshop_id" id="hshop_id" value="{$smarty.request.shop_id}"/>
        {foreach  item='item' from=$services}
        <input type="hidden" name="qty{$item.plugin_id}" id="qty{$item.plugin_id}" value="1"/>
        {/foreach}
  

