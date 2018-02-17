 <script>
    
     jQuery(document).ready(function() {
     window.parent.document.getElementById("manage_content").style.backgroundImage = 'none';
   
     });
     
    
</script>
 {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createDiscount&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Discount'}</a>
</div>
 {/securityutil_checkpermission_block}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Discount'}</h3>
</div>


<form class="z-form" id="option_form" action="" method="post">
     <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <th>{gt text='Actions'}</th>
                    <th><a class='{$sort.class.discount_code}' href='{$sort.url.discount_code|safetext}'>{gt text='Discount code'}</a></th>
                    <th><a class='{$sort.class.discount}' href='{$sort.url.discount|safetext}'>{gt text='Discount'}</a></th>
                    <th>{gt text='Status'}</th>
            
                </tr>
            </thead>
            <tbody>
                {foreach from=$discountItems item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>
                    <a href="{modurl modname='ZSELEX' type='admin' func='deleteDiscount' shop_id=$smarty.request.shop_id discount_id=$item.discount_id}">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png' title='Delete' alt="Delete" class='tooltips'}</a>
                    <a href="{modurl modname='ZSELEX' type='admin' func='editDiscount' discount_id=$item.discount_id shop_id=$smarty.request.shop_id}" >{img modname='core' set='icons/extrasmall' src='xedit.png' title='Edit' alt="Edit" class='tooltips'}</a>
                    </td>
                    <td>
                        {$item.discount_code|safetext}
                    </td>
                    <td>
                         {$item.discount|safetext}
                    </td>
                     <td>
                       {$aStatus[$item.status]} 
                    </td>
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No discounts options found.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
      
    </div>
</form>

{pager rowcount=$total limit=$itemsperpage posvar='startnum' maxpages=10}



<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>