<style>
    
    </style>
{*{shopheader}*}
 <script>
     //alert('helloo');
     //document.getElementById('backshield').style.display = 'block';
     jQuery(document).ready(function() {
     window.parent.document.getElementById("manage_content").style.backgroundImage = 'none';
   
   
     //document.getElementById("manage_content").style.display = 'none';
     
     
     });
     
    
</script>
{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createManufacturer&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Manufacturer'}</a>
</div>
 {/securityutil_checkpermission_block}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Manufacturer list'}</h3>
</div>

<script type="text/javascript">
function confirmation()
{
if(confirm(Zikula.__("Do you really want to delete this manufacturer?"))==true)
return true;
else
return false;
}

</script>

<form class="z-form" id="category_filter" action="" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s manufacturer listed' plural='Filter %1$s, %2$s manufacturers listed' count=$total tag1=$filteractive tag2=$total}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Manufacturer Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='manufacturers' shop_id=$smarty.request.shop_id}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>
    </fieldset>
</form>
<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='processbulkaction'}" method="post">
     <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                   <!--   <th></th> -->
                    <th>{gt text='Actions'}</th>
                    <th><a class='{$sort.class.manufacturer_id}' href='{$sort.url.manufacturer_id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.manufacturer_name}' href='{$sort.url.manufacturer_name|safetext}'>{gt text='Manufacturer'}</a></th>
                    <th>{gt text='Status'}</th>
                  
                </tr>
            </thead>
            <tbody>
                {foreach from=$manufacturers item='manufacturer'}
                <tr class="{cycle values='z-odd,z-even'}">
                   <!--   <td><input type="checkbox" name="zselex_selected_categorys[]" value="{$categoryitem.category_id}" class="zselex_checkbox" /></td> -->
                    <td>
                    <a  href="{modurl modname='ZSELEX' type='admin' func='deleteManufacturer' shop_id=$shop_id mnfr_id=$manufacturer.manufacturer_id shop_id=$smarty.request.shop_id}">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png' title='Delete' alt="Delete" class='tooltips'}</a>
                    <a href="{modurl modname='ZSELEX' type='admin' func='editManufacturer' shop_id=$shop_id mnfr_id=$manufacturer.manufacturer_id}" >{img modname='core' set='icons/extrasmall' src='xedit.png' title='Edit' alt="Edit" class='tooltips'}</a>
                    </td>
                    <td>{$manufacturer.manufacturer_id|safetext}</td>
                    <td>{$manufacturer.manufacturer_name|safetext}</td>
                    <td>{$aStatus[$manufacturer.status]|safetext}</td>
                    
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No manufacturere currently in database.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
       
    </div>
        {*<div class="z-buttons z-formbuttons">
            
            <a href="javascript:close()"  title="{gt text="Close"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Close" __title="Close"} {gt text="Close"}</a>
            
       </div>*}
</form>
            

{pager rowcount=$total limit=$itemsperpage posvar='startnum' maxpages=10}

{*{adminfooter}*}


<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>