
{shopheader}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/basket.js'}


<script>

    function confirmation(){
        if(confirm(Zikula.__("Activate demo period for this service?"))==true)
            return true;
        else
            return false;
    }
    
</script>


<div class="z-admin-content-pagetitle">
    <h3>{gt text='Choose Services'}</h3>
</div>
<div>
    <div style="overflow:auto;" class="z-formrow">
        <label for="plugin"></label>
        <table width="30%"  class="z-datatable">
            <thead>
                <tr>
                    <td></td>
                    <td><b>{gt text='Services'}</b></td>
                    <td><b>{gt text='Description'}</b></td>
                    <td><b>{gt text='Service Dependency'}</b></td>
                    <td><b>{gt text='Quantity'}</b></td>
                    <td><b>{gt text='Price'}</b></td>
                    <td><b>{gt text='Bought'}</b></td>
                </tr>
            </thead>
            <tbody>
                {foreach  item='item' from=$plugin}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>
                   {if $item.cantbuy < 1}  <!-- condition1: if not qualified to buy -->
                          <!-------    ADD TO CART LINK   ---->
                        <span style="float:left">      
                            <a href="javascript:document.servicecart{$item.plugin_id}.submit()">{gt text='Add to cart'}</a>
                        </span>
                        <form id="servicecart{$item.plugin_id}" name="servicecart{$item.plugin_id}" action="{modurl  modname='ZSELEX' type='admin' func='serviceCart'}"  method="post"  target="_blank">
                            <input type="hidden" id="cart_quantity{$item.plugin_id}" name="cart_quantity" value="1" />
                            <input type="hidden" name="qty_based" value="{if $item.qty_based eq 0}0{else}1{/if}" /> 
                            <input type="hidden" name="serviceId" value="{$item.plugin_id}" /> 
                            <input type='hidden' name='servicetype' value="{$item.type}" >
                            <input type='hidden' name='serviceprice' value="{$item.price}" >
                            <input type='hidden' name='bundle' value="{$item.bundle}" >
                            <input type='hidden' name='bundle_id' value="{$item.bundle_id}" >
                            <input type='hidden' name='top_bundle' value="{$item.top_bundle}" >
                            <input type="hidden" name="shop_id" value="{$smarty.request.shop_id}" />
                            <input type="hidden" name="isDepended" value="{$item.service_depended}" /> 
                        </form>
                          <!-------    ADD TO CART LINK ENDS     ---->

                     <!-------    DEMO LINK     ---->
                      {if $item.demo_status eq 1}
                        <span style="float:left;margin-left: 26px">  
                            <a href="javascript:document.democart{$item.plugin_id}.submit()" onclick="return confirmation()">{gt text='Demo'}</a>
                        </span>
                        <form id="democart{$item.plugin_id}" name="democart{$item.plugin_id}" action="{modurl  modname='ZSELEX' type='admin' func='demoCart'}"  method="post">
                            <input type="hidden" id="cart_quantitydemo{$item.plugin_id}" name="cart_quantity" value="1" />
                            <input type="hidden" name="qty_based" value="{if $item.qty_based eq 0}0{else}1{/if}" /> 
                            <input type="hidden" name="serviceId" value="{$item.plugin_id}" /> 
                            <input type='hidden' name='servicetype' value="{$item.type}" >
                            <input type='hidden' name='serviceprice' value="{$item.price}" >
                            <input type='hidden' name='demoperiod' value="{if $item.demoperiod < 1}1{else}{$item.demoperiod}{/if}" >
                            <input type='hidden' name='bundle' value="{$item.bundle}" >
                            <input type='hidden' name='bundle_id' value="{$item.bundle_id}" >
                            <input type='hidden' name='top_bundle' value="{$item.top_bundle}" >
                            <input type="hidden" name="shop_id" value="{$smarty.request.shop_id}" /> 
                            <input type="hidden" name="service_depended" value="{$item.service_depended}" /> 
                        </form>
                        {/if}
                     <!-------    DEMO LINK ENDS   ---->
                     {else}
                         {$item.msg}
                {/if}  <!-- condition1 ends -->
                    </td>
                    <td>
                        {$item.plugin_name} 
                    </td>

                    <td>
                         {foreach from=$item.bundleitems item='bundleitem' key='key'}
                             {$key+1} . {$bundleitem.service_name} - {$bundleitem.qty}<br>
                         {/foreach}
                    </td>

                    <td>
                        {if $item.top_bundle eq 0}
                                <table>
                              {foreach from=$item.buy.depended_services item='buyitem' key='key'}
                                 <tr>
                                     <td>{$key+1}</td>
                                     <td>{$buyitem.type}</td>
                                     <td>{$buyitem.canbuystatus}</td>
                                 <tr>    
                             {foreachelse} 
                                
                             {/foreach}
                               </table>
                             {else}
                                  <table>
                             {foreach from=$item.bundlebuy.depended_services item='bndlebuyitem' key='bndlekey'}
                                  <tr>
                                    <td>{$bndlekey+1}</td>
                                    <td>{$bndlebuyitem.type}</td>
                                    <td>{$bndlebuyitem.canbuystatus}</td>
                                 </tr>
                             {foreachelse} 
                                
                             {/foreach}
                                  </table>
                             {/if}
                    </td>

                    <td>
                        <input type="text" value="1" id="number" size="2" {if $item.qty_based eq 0} disabled="disabled" {/if} 
                               onkeyup="addQuantity({$item.plugin_id} , this.value);getServiceCount(this.value , {$item.price} , {$item.plugin_id});">
                    </td>

                    <td id="price{$item.plugin_id}">
                        {$item.price}
                    </td>

                    <td>
                        {$item.servicePurchasedCount}
                    </td>

                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>

<input type="hidden" name="hshop_id" id="hshop_id" value="{$smarty.request.shop_id}"/>
{foreach  item='item' from=$plugin}
<input type="hidden" name="qty{$item.plugin_id}" id="qty{$item.plugin_id}" value="1"/>
{/foreach}



{pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages=10}            

<script type="text/javascript">
    Zikula.UI.Tooltips($$('.toolmsg'));
</script>


{adminfooter}