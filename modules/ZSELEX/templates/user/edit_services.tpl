
{insert name='getstatusmsg'}


<div class="Block">
    <div class="HalfWidth left">
        <div class="Section left">
            <div class="Settings_Image_Section">
                {if $all_ser}
                <a href="{modurl modname="ZSELEX" type="admin" func="quickbuy" shop_id=$shop_id}">
                   {else}
                   <a href="{modurl modname="ZSELEX" type="admin" func="quickbuy" shop_id=$shop_id all_ser=1}">
                   {/if}
                   <div class="Setting_section">
                            <div class="Setting_section_image">
                                <img src="themes/CityPilot/images/EditeOrange.png" />
                            </div>
                            <div class="Setting_section_Orange">
                                {if $all_ser}
                                <p>{gt text='Hide all services'}<span class="ArrowShade"></span></p>
                                {else}
                                <p>{gt text='All services'}<span class="ArrowShade"></span></p>
                                {/if}
                            </div>
                        </div>
                    </a>
            </div>
        </div>
        <div class="Backend3Block">
             {if $all_ser}
            <p>{gt text='Only show bundles'}.</p>
             {else}
            <p>{gt text='Show all services'}.</p>
              {/if}   
        </div>
    </div>
    <div class="HalfWidth right">
        <div class="Section left">
            <a href="{modurl modname="ZSELEX" type="admin" func="shopinnerview" shop_id=$shop_id}">
               <div class="Settings_Image_Section">
                    <div class="Setting_section">
                        <div class="Setting_section_image">
                            <img src="themes/CityPilot/images/Spaner.png" />
                        </div>
                        <div class="Setting_section_Orange">
                            <p>{gt text='Advanced'} <span class="ArrowShade"></span></p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="Backend3Block">
            <p>{gt text='Go to the Advanced View of the backend'}.</p>
        </div>
    </div>
</div>
{foreach item='items' key=index1 from=$servicesPurchased}
<div class="Block">
    {foreach item='item' key=index from=$items}
    <div class="HalfWidth {cycle values="left,right"}">
         <div class="Section left">
                       <a href='{modurl modname="ZSELEX" type="admin" func=$item.func_name shop_id=$shop_id}'>
                                   <div class="Settings_Image_Section">
                                       <div class="Setting_section">
                                           <div class="Setting_section_image">
                                               <h2 class="Backend3Head">{$item.plugin_name}</h2>
                                           </div>
                                           <div class="Setting_section_Orange">
                                               <h4 class="PriceH4">
                                                   DKK {$item.price}
                                               </h4>
                                           </div>
                                       </div>
                                   </div>
                               </a>
                              

                           </div>
                           <div class="Backend3Block">
                                {if $item.top_bundle eq 1}
                               <ul class="Backend3List">
                                   {foreach item='bundleitem' key=index1 from=$item.bundleitems}
                                   <li>{$bundleitem.service_name} - {$bundleitem.qty}</li>
                                   {/foreach}
                               </ul>
                               {else}
                                 {$item.description}
                                {/if}
                           </div>

                           </div>
                           {/foreach}
              </div>
                           {/foreach}

                           {*{pager rowcount=$count limit=$itemsperpage posvar='startnum'} *}
