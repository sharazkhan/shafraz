<script>
    jQuery(document).ready(function(){
        jQuery(".HeadingTD").click(function(){
            jQuery("#HidenRow"+this.id).slideToggle("fast");
            if(jQuery("#HidenRowDep"+this.id)){
                jQuery("#HidenRowDep"+this.id).slideToggle("fast");
            }
            
        });
    });

</script>





<p class="pagetree">{gt text="You are her"}: {gt text="Administration"} > <span class="orange_text">{gt text="Edit Services"}</span></p>
<h2 class="Backend_h2">{gt text="Edit Services"}</h2>
<p>{gt text="Her har du mulighed for at redigere services til din butik. Vi har sammensat nogle pakker af populære services, som vil dække manges behov. Men du har også mulighed for selv at vælge, og kombinere services efter din butiks behov."}</p>
<!--    <h2 class="SubHead_h2">Alle dine services: <span class="ServiceHeadSpan">Anvendt</span></h2> -->

<table class="ServiceTable">
    <tr class="HeadingTD"><td><h2 class="SubHead_h2">{gt text='Alle dine services'}:</h2></td><td class="TextRight"><h2 class="SubHead_h2"><span class="ServiceHeadSpan">{gt text='Anvendt'}</span></h2></td></tr>
</table>


{foreach item='service' key=index from=$servicesPurchased}
<div class="CurvedBorder">
    <div class="SeviceHover_EditService">
        <table class="ServiceTable">
            <tr class="HeadingTD" id="{$service.plugin_id}"><td>{$service.plugin_name} ({$service.quantity})</td><td class="TextRight">{$service.availed}</td></tr>
            <tr {*class="HidenRow"*} style="display:none;" id="HidenRow{$service.plugin_id}">
                <td colspan="2" style="padding:0px;">
                    <table id="ServicePopOverTable" class="FullWidth" width="100%">
                        <tr><td colspan="2" ><p>{lingualtext content=$service.content}</p> </td></tr>
                        <tr class="BottonRowEdit">
                            <td style="width:35%" colspan="2">

                                {if $service.is_editable eq 1 && $service.func_name neq ''}
                                <img src="{$themepath}/images/Edit_Black.png" alt="Edit" style="vertical-align: top" />
                                <a href='{modurl modname="ZSELEX" type="admin" func=$service.func_name shop_id=$shop_id}'>
                                    {gt text='Rediger service'}
                                    {/if}
                                </a> 
                                {if $service.quantity > 0}
                                &nbsp;&nbsp; 
                                <a href="{modurl modname="ZSELEX" type="admin" func="reduceServiceSimpleView" shop_id=$smarty.request.shop_id sid=$service.id}">
                                   {else}  
                                   <a href="#">
                                        {/if}  
                                        &divide; {gt text='Reducer antal'}
                                    </a> 
                                    {if $service.quantity < $service.original_quantity}
                                    &nbsp;&nbsp;  <a href="{modurl modname="ZSELEX" type="admin" func="cancelReduced" shop_id=$smarty.request.shop_id sid=$service.id src=simple}"> x {gt text="Cancel"}  </a> 
                                    {/if}  
                                    {if $admin} 
                                    &nbsp;&nbsp;<a id="confirmdialog" href="{modurl modname="ZSELEX" type="admin" func="deleteConfiguredService" shop_id=$smarty.request.shop_id sid=$service.id}"> 
                                                   x {gt text='Slet service'}
                                                   {/if}
                                </a>
                        </td>
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


