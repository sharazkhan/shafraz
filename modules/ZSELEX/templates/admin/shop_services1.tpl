
<script>
    function confirmation(){
    if(confirm(Zikula.__("Activate demo period for this bundle?"))==true)
        return true;
    else
        return false;
}
</script>
<h2 class="Backend_h2 Myservice">{gt text='My Services'}</h2>
<div class="MyShopServices">
    {foreach item='bundle' key=index from=$bundles}
        {assign var="bundlecount" value=$bundle|@count}
        {foreach item='bundle' key=index1  from=$bundle}
            <div class="MyServiceGroup{if $index1+1 eq $bundlecount} LastService {/if}">
                <div class="MyServiceSection">
                    <div class="ServiceHead">
                        {$bundle.bundle_name}
                    </div>
                    <div class="ServiceText">
                        {assign var="info" value=$bundle.content|unserialize}   
                        {$info.$thislang.infomessage}
                        <br /><br />
                        <a href="{modurl modname="ZSELEX" type="admin" func="buybundle" shop_id=$smarty.request.shop_id bundle_id=$bundle.bundle_id}">{gt text='More about'} {$bundle.bundle_name}</a>
                    </div>
                    <div class="MyservicePrice">{displayprice amount=$bundle.bundle_price|safetext} {gt text='p/m'}
                    </div>
                </div>
                {if $bundle.cantbuy < 1} <!-- cant buy--> 

                    {if !$bundle_exist} <!-- bundle nt exist check   -->
                        {if $bundle.bundle_type neq 'additional'}
                            {assign var="demo_period" value=$bundle.demoperiod}
                            {if $bundle.demo_status eq 1}
                                <a href="javascript:document.democart{$bundle.bundle_id}.submit()" onclick="return confirmation()">
                                    <button class="MyserviceBtn">
                                        {gt text='Try free for'} {$bundle.demoperiod} {gt text='days'} <img src="{$themepath}/images/ArrowShadow.png" />
                                    </button>
                                </a>
                            {/if}    
                        {/if}

                    {else}<!-- if bundle exist -->

                        {*  {if !$bundle.service_exist} <!-- service nt exist --> *}

                        {if $bundle.button_show}  <!-- button show - eligible for buttons--> 

                            {if $existing_bundle.bundle_status eq 1 AND !$existing_bundle.running}
                                <a href="javascript:document.servicecart{$bundle.bundle_id}.submit()">
                                    <button class="MyserviceBtn">
                                        {gt text='Upgrade'} <img src="{$themepath}/images/ArrowShadow.png" />
                                    </button>
                                </a>
                            {/if}
                            {if $existing_bundle.running}
                                {if $existing_bundle.time_left > 0}   
                                    {assign var="demo_period" value=$existing_bundle.time_left}
                                    {if $bundle.demo_status eq 1}
                                        <a href="javascript:document.democart{$bundle.bundle_id}.submit()" onclick="return confirmation()">
                                            <button class="MyserviceBtn">
                                                {gt text='Try free for'} {$existing_bundle.time_left} {gt text='days'} <img src="{$themepath}/images/ArrowShadow.png" /> 
                                            </button>
                                        </a>
                                    {/if}
                                {/if}
                            {/if}  
                        {/if} <!-- button show -->
                        {*  {/if} <!-- service nt exist --> *}
                    {/if} <!-- bundle nt exist check   -->
                {/if} <!-- cant buy-->
                {if $bundle.service_exist AND $bundle.bundle_type neq 'additional'}
                    <div  align="center">{gt text='You have this bundle'}</div>
                {/if} 
                {if $bundle.service_exist AND $bundle.bundle_type eq 'additional'}
                    <div  align="center">{gt text='You have '}{$bundle.service_exist_details.quantity} {gt text='of this bundle'}</div>
                {/if} 

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
                    <input type='hidden' name='bundle_type' value="{$bundle.bundle_type}" >
                    <input type="hidden" name="shop_id" value="{$smarty.request.shop_id}" /> 
                    <input type="hidden" name="service_status" value="1" /> 
                    <input type="hidden" name="service_depended" value="{$bundle.service_depended}" />
                    <input type="hidden" name="source" value="myservice" />
                </form>

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
                    <input type="hidden" name="service_depended" value="{$bundle.service_depended}" />
                    <input type="hidden" name="source" value="myservice" />
                </form>     

            </div>

        {/foreach}
    {/foreach}
</div>

