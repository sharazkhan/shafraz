{pageaddvar name='javascript' value='modules/ZSELEX/javascript/shop_services.js'}
<script>
    function confirmation(){
    if(confirm(Zikula.__('Activate demo period for this bundle?','module_zselex_js'))==true)
        return true;
    else
        return false;
}
</script>
<input type="hidden" id="shop_id" value="{$smarty.request.shop_id}">
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
                       {if !$bundle_exist} <!-- bundle never purchased   -->
                            {if $bundle.bundle_type neq 'additional' AND !$bundle.is_free}
                                {assign var="demo_period" value=$bundle.demoperiod}
                                {if $bundle.demo_status eq 1}
                                    <a href="javascript:document.democart{$bundle.bundle_id}.submit()" onclick="return confirmation()">
                                        <button class="MyserviceBtn">
                                            {gt text='Try free for'} {$bundle.demoperiod} {gt text='days'} <img src="{$themepath}/images/ArrowShadow.png" />
                                        </button>
                                    </a>
                                {/if}    
                            {/if}
                         {else} 
                          
                            {if $bundle_exist AND $existing_bundle.running}<!--Demo-->
                                {assign var="demo_period" value=$existing_bundle.time_left}
                                  {if $serviceBundleExist > 0}<!-- If Not Removed-->
                                    {if $bundle.button_show}  <!-- button show - eligible for buttons--> 
                                           {if $bundle.demo_status eq 1}
                                               {if $bundle.service_exist AND $bundle.bundle_type eq 'additional'}
                                                   <div style="width:180px">
                                                    <div style="width:85px;float:left;padding:0 10px 0 0">
                                                        <a href="#" onclick="return downgradeAdditionalConfirm({$bundle.bundle_id})">
                                                            <button class="MyserviceBtnGray"><img width="15px" src="images/global/arrow_down_white.png">
                                                            </button>
                                                        </a>
                                                    </div><div style="width:85px;float:left">
                                                        <a href="javascript:document.democart{$bundle.bundle_id}.submit()" onclick="return confirmation()">
                                                            <button class="MyserviceBtn"><img width="15px" src="images/global/arrow_up_white.png">
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                               {else}    
                                              <a href="javascript:document.democart{$bundle.bundle_id}.submit()" onclick="return confirmation()">
                                                  <button class="MyserviceBtn">
                                                      {gt text='Try free for'} {$existing_bundle.time_left} {gt text='days'} <img src="{$themepath}/images/ArrowShadow.png" /> 
                                                  </button>
                                              </a>
                                               {/if}
                                          {/if}
                                    {else}
                                       {if $existing_bundle.bundle_id neq $bundle.bundle_id}
                                        <!-- Downgrade.. -->
                                           <a href="#" onclick="return downgradeConfirm({$bundle.bundle_id})">
                                            <button class="MyserviceBtnGray">
                                                {gt text='Downgrade to this'} <img src="{$themepath}/images/ArrowShadow.png" />
                                            </button>
                                          </a>
                                       {/if}
                                  {/if}
                                 {else}<!-- If Removed-->
                                      {if $bundle.demo_status eq 1}
                                          {if !$bundle.is_free}
                                              <a href="javascript:document.democart{$bundle.bundle_id}.submit()" onclick="return confirmation()">
                                                  <button class="MyserviceBtn">
                                                      {gt text='Try free for'} {$existing_bundle.time_left} {gt text='days'} <img src="{$themepath}/images/ArrowShadow.png" /> 
                                                  </button>
                                              </a>
                                           {/if}
                                       {/if}
                                 {/if}
                            {else}<!--Upgrade-->
                                {if $serviceBundleExist > 0}
                                    {if $bundle.button_show}  
                                      <!--Upgrade..-->
                                       <a href="javascript:document.servicecart{$bundle.bundle_id}.submit()">
                                        <button class="MyserviceBtn">
                                            {gt text='Upgrade to this'} <img src="{$themepath}/images/ArrowShadow.png" />
                                        </button>
                                      </a>
                                    {else}
                                        {if $existing_bundle.bundle_id neq $bundle.bundle_id}
                                        <!-- Downgrade.. -->
                                           <a href="#" onclick="return downgradeConfirm({$bundle.bundle_id})">
                                            <button class="MyserviceBtnGray">
                                                {gt text='Downgrade to this'} <img src="{$themepath}/images/ArrowShadow.png" />
                                            </button>
                                          </a>
                                       {/if}
                                    {/if}
                                 {else}
                                  <!--Upgrade..(always)-->
                                   <a href="javascript:document.servicecart{$bundle.bundle_id}.submit()">
                                    <button class="MyserviceBtn">
                                        {gt text='Upgrade'} <img src="{$themepath}/images/ArrowShadow.png" />
                                    </button>
                                   </a>
                                 {/if}
                            {/if}
                           
                      {/if}
                    
                {/if} <!-- cant buy-->
                {if $bundle.service_exist AND $bundle.bundle_type neq 'additional'}
                      <button class="MyserviceBtnGray" onclick="return removeConfirm({$bundle.bundle_id})">
                      {gt text='Remove Bundle'} <img src="{$themepath}/images/ArrowShadow.png" />
                      </button>
                    <div  align="center">{gt text='You have this bundle'}</div>
                {/if} 
                {if $bundle.service_exist AND $bundle.bundle_type eq 'additional'}
                    <div  align="center">
                        {*{gt text='You have'} {$bundle.service_exist_details.quantity} {gt text='of this bundle'}*}
                        {gt text='You have %s of this bundle' tag1=$bundle.service_exist_details.quantity}
                    </div>
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

<div id="backshield" class="backshield" style="height: 10157px;display:none"></div>
<div id="dowgrade_alert" class="basket_content"  style="display:none">
   
</div>

