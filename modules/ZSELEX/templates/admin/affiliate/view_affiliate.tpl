
{adminheader}
  <div class="z-admin-content-pagetitle">
   <a href="index.php?module=zselex&amp;type=admin&amp;func=createaffiliate" class="z-iconlink z-icon-es-add">{gt text='Create Affiliate'}</a>
  </div>


<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='New affiliate list'}</h3>
</div>

<form class="z-form" id="zselex_bulkaction_form" action="" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                
                    <th>{gt text='Actions'}</th>
                    <th>{gt text='Affiliate ID'}</th>
                    <th>{gt text='Name'}</th>
                    <th>{gt text='Image'}</th>
                    <th>
                        {gt text='Sort Order'}
                         {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __value="SORT"}
                    </th>
                   
                  
                </tr>
            </thead>
            <tbody>
                {foreach from=$affiliates item='affiliates'}
                <tr class="{cycle values='z-odd,z-even'}">
                <td>
                    <a href="{modurl modname='ZSELEX' type='admin' func='deleteaffiliate' aff_id=$affiliates.aff_id}">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png' title='Delete Affiliate' alt="Delete Affiliate" class='tooltips'}</a>
                    <a href="{modurl modname='ZSELEX' type='admin' func='modifyaffiliate' aff_id=$affiliates.aff_id}">{img modname='core' set='icons/extrasmall' src='xedit.png' title='Edit Affiliate' alt="Edit Affiliate" class='tooltips'}</a>
                </td>
                <td>{$affiliates.aff_id|safetext}</td>
                <td>{$affiliates.aff_name|safetext}</td>
                <td>{$affiliates.aff_image|safetext}</td>
                <td align="center">
                    <input type="text" size="5" name="sortorder[{$affiliates.aff_id}]" value="{$affiliates.sort_order}">
                </td>
               
            </tr>
            {foreachelse}
            <tr class="z-datatableempty"><td colspan="21">{gt text='No affliates currently in database.'}</td></tr>
            {/foreach}
        </tbody>
    </table>
    
</div>
</form>
  {pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages=10}


<script type="text/javascript">
   
     
</script>

{adminfooter}


<script type="text/javascript">
    // <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
    // ]]>
</script>