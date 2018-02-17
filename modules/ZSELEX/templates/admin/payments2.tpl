{ajaxheader modname='Tag' ui=true}
{pageaddvarblock}
<script type="text/javascript">
    document.observe("dom:loaded", function() {
        Zikula.UI.Tooltips($$('.tooltips'));
    });
</script>
{/pageaddvarblock}

{if $smarty.request.shop_id}
    {shopheader}
{else}    
{adminheader}
{/if}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text="Payments Available"}</h3>
</div>

{insert name="getstatusmsg"}

<table class="z-datatable">
    <thead>
        <tr>
           
            <td>{gt text='Payment Methods'}</td>
            
            <td>{gt text='Options'}</td>
        </tr>
    </thead>
    <tbody>
        {foreach from=$payments item='payment'}
            <tr class="{cycle values="z-odd,z-even"}">
                <td>{$payment.method|safetext}</td>
               
                <td>
                    <a href="{$payment.edit_link}">{img modname='core' set='icons/extrasmall' src='xedit.png' __title='Edit' __alt='Edit' class='tooltips'}</a>
                </td>
            </tr>
         {/foreach}
    </tbody>
</table>
{adminfooter}