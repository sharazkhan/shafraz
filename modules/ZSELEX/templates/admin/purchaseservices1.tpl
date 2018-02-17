
{shopheader}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/basket.js'}


<style>

    .basket_content {
        background-color: white;
        border: 16px solid black;
        left: 25%;
        min-height: 100px;
        overflow: auto;
        position: fixed;
        top: 30%;
        width: 750px;
        z-index: 10002;
    }
    .backshield {
        background-color: #333333;
        height: 200%;
        left: 0;
        opacity: 0.8;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

</style>


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
  <!--  
<div id="admCart" align="center" onClick='displayBasket({$smarty.request.shop_id})' style="cursor:pointer">
   
    <b>cart({$count})</b>
 
</div> 
  -->
<!--<form class="z-form" action="{modurl modname="ZSELEX" type="admin" func="submitshopuser"}" method="post" enctype="multipart/form-data">-->
<div>
    <div class="z-formrow">
                <label for="plugin"></label>
                <table width="30%"  class="z-datatable">
                     <thead>
                        <tr>
                            <td><b>{gt text='Services'}</b></td>
                            <td><b>{gt text='Description'}</b></td>
                            <td><b>{gt text='Service Dependency'}</b></td>
                            <td><b>{gt text='Quantity'}</b></td>
                            <td><b>{gt text='Price'}</b></td>
                            <td><b>{gt text='Bought'}</b></td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                    {foreach  item='item' from=$plugin}
                        
                        {if $item.type neq 'theme'}
                    <tr class="{cycle values='z-odd,z-even'}">
                        <td>
                           
                             {if $item.contents.$thislang.infomessage neq ''}
                                {if $item.contents.displayinfo  eq 'yes'}
                                <a class="infoclass"  id="serviceInfo{$item.plugin_id}" href="{modurl modname='ZSELEX' type='info' func='displayserviceInfo' id=$item.plugin_id}" title="{gt text=$item.contents.$thislang.infotitle}">
                                <img  src="{$baseurl}images/icons/extrasmall/info.png">
                                </a>
                                 {/if}
                             {/if}
                            {$item.plugin_name} 
                           
                            {*{$item.contents.$thislang.infotitle} *}
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
                        <td><input type="text" value="1" id="number" size="2" {if $item.qty_based eq 0} disabled="disabled" {/if} 
                            onkeyup="addQuantity({$item.plugin_id} , this.value);getServiceCount(this.value , {$item.price} , {$item.plugin_id});">
                       </td>
                      
                        <td id="price{$item.plugin_id}">{$item.price}</td>
                       <!-- <td><img class='toolmsg'  title="Add to basket" border='0'  src='{$baseurl}zselexdata/basket.jpg' style="cursor:pointer" onclick="addToBasket1({$item.plugin_id} ,  '{$item.plugin_name}'  , '{$item.type}' , {$item.price} , document.getElementById('qty'+{$item.plugin_id}).value ,  {$item.qty_based} ,document.getElementById('hshop_id').value)" /></td>-->
                       
                       <td>{$item.servicePurchasedCount}</td>
                       
                       <td>
            {if $item.cantbuy > 0}  
                 {$item.msg}
             {/if}
                {if $item.cantbuy < 1}           
                  {if $item.disabled eq 1}
                      <i>Not Applicable</i>
                  {else}    
                  
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
                  
               {if $item.disabledemo eq 0}   
                 {if $item.demo eq 1}    
                   {* {if $item.democount < 1} *}
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
                        <input type='hidden' name='bundle' value="{$item.bundle}" >
                        <input type='hidden' name='bundle_id' value="{$item.bundle_id}" >
                        <input type='hidden' name='top_bundle' value="{$item.top_bundle}" >
                        <input type="hidden" name="shop_id" value="{$smarty.request.shop_id}" /> 
                        <input type="hidden" name="isDepended" value="{$item.service_depended}" /> 
                    </form>
                     {else}
                      {/if}   
                        </td>
                       
                    </tr>
                  {/if}
                {/if}
                {/if}
         {/if}
        
        {/if} <!-- buy status -->
                  
                  
                    <script type="text/javascript">
                    var defwindowajax = new Zikula.UI.Window($('serviceInfo{/literal}{$item.plugin_id}{literal}'),{resizable: true });
                    </script>
                     
                    {/foreach}
                    </tbody>
                </table>
            </div>
                </div>
                        
          

     <input type="hidden" name="hshop_id" id="hshop_id" value="{$smarty.request.shop_id}"/>
    {foreach  item='item' from=$plugin}
    <input type="hidden" name="qty{$item.plugin_id}" id="qty{$item.plugin_id}" value="1"/>
   
    {/foreach}
    <input type="hidden" name="totalcounts" id="totalcounts" value="0"/>
    <input type="hidden" name="totalcount" id="totalcount" value="0"/>
<!--</form>-->
    
    
<div id="light" class="basket_content" style="display:none"></div>
<div id="backshield" class="backshield" style="height: 2157px;display:none" onClick='closeWindow();'></div>
                
    {pager rowcount=$total_count limit=$itemsperpage posvar='startnum'}            
                
<script type="text/javascript">
    // var defaultTooltip = new Zikula.UI.Tooltip($('toolmsg'));

    Zikula.UI.Tooltips($$('.toolmsg'));
</script>
 
 
 


{adminfooter}