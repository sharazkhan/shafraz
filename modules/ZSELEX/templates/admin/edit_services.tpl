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
<p>{gt text="Here you have the option of editing services at your store. We have put together some packages of popular services that will cover many needs. But you also have the option to choose and combine services at your store needs."}</p>
<!--    <h2 class="SubHead_h2">Alle dine services: <span class="ServiceHeadSpan">Anvendt</span></h2> -->

<table class="ServiceTable">
    <tr class="HeadingTD"><td><h2 class="SubHead_h2">{gt text='All your services'}:</h2></td><td class="TextRight"><h2 class="SubHead_h2"><span class="ServiceHeadSpan">{gt text='Used'}</span></h2></td></tr>
</table>


{foreach item='service' key=index from=$servicesPurchased}
<div class="CurvedBorder">
    <div {if $service.is_editable eq 1 && $service.func_name neq ''} class="SeviceHover_EditService" {/if}>
        <table class="ServiceTable">
            <tr class="HeadingTD" id="{$service.plugin_id}"><td>{$service.plugin_name} ({if $service.type eq 'diskquota'}{ownerfolderquota shop_id=$shop_id}{else}{$service.quantity}{/if})</td><td class="TextRight">{serviceusedcount shop_id=$shop_id type=$service.type}{*{$service.availed}*}</td></tr>
              {if $service.is_editable eq 1 && $service.func_name neq ''}
            <tr {*class="HidenRow"*} style="display:none;" id="HidenRow{$service.plugin_id}">
                <td colspan="2" style="padding:0px;">
                    <table id="ServicePopOverTable" class="FullWidth" width="100%">
                        <tr><td colspan="2" ><p>{lingualtext content=$service.content}</p> </td></tr>
                        <tr class="BottonRowEdit">
                            <td style="width:35%" colspan="2">

                                    {if $service.is_editable eq 1 && $service.func_name neq ''}
                                <img src="{$themepath}/images/Edit_Black.png" alt="Edit" style="vertical-align: top" />
                                <a href='{modurl modname="ZSELEX" type="admin" func=$service.func_name shop_id=$shop_id}'>
                                    {gt text='Edit service'}
                                </a> 
                                     {/if}
                                   
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
          {/if}
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


