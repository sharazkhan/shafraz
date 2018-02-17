{*{ajaxheader modname='ZSELEX' filename='delete_bulk.js' ui=true}*}
{ajaxheader imageviewer="true"}
{shopheader}
{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/zselex_admin_validation.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/test1.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquery.jscroll.min.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/jquery.jscroll.js'}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/bundles.js'}
<style>

.basket_content {
       background-color: white;
        /* border: 5px solid #DD511D;*/
        left: 50%;
        min-height: 50px;
        margin-left: -270px;
        /* overflow: hidden;*/
        height: auto;
        position: absolute;
        padding: 20px;
        top: 10%;
        width: 500px;
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
<div id="backshield" class="backshield" style="height: 9157px;display:none"></div> 
<div id="editPurBundle" class="basket_content" style="display:none">
</div> 

<div class="z-admin-content-pagetitle">
    <h3>{gt text='Configured Services'}</h3>
</div>
<input type='hidden'  id='formID' value='news_bulkaction_form' />
<form class="z-form" id="news_bulkaction_form" action="{modurl modname="ZSELEX" type="admin" func="configuredServices" shop_id=$smarty.request.shop_id}" method="post" enctype="multipart/form-data">
      <div class="z-formrow">
        <label for="plugin"></label>
        {if $smarty.request.shop_id neq ''}
        {if $serviceCount neq '0'}
        <table width="30%" class="z-datatable">
            <thead>
                <tr>
                    {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
                    <td></td>
                    {/securityutil_checkpermission_block}

                    <td><b>{gt text='Bundles'}</b></td>
                 
                    <td><b>{gt text='Quantity'}</b></td>
                  
                    <td><b>{gt text='Status'}</b></td>
                   
                </tr>
            </thead>
            <tbody>
                {foreach  item='item' from=$servicesPurchased}
                    <tr   class="{cycle values='z-odd,z-even'}" {*onclick="editPurchasedBundles('{$item.service_bundle_id}' , '{$smarty.request.shop_id}' , '{$item.bundle_id}')"*}>
                    {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
                    <td>
                        {if $item.bundle_type neq 'additional'}
                        <input {if $item.bundle_type eq 'additional'}disabled{/if} type="checkbox" id="buyed_bundles" name="news_selected_articles[]" value="{$item.service_bundle_id}" class="news_checkbox buyed_bundles" />
                        {/if}
                     </td>
                    {/securityutil_checkpermission_block}
                    <td>
                        {$item.bundle_name} - <i><b>{$item.bundle_type}</b></i> 
                    </td>
                   {* <td>{$item.original_quantity}</td>*}
                    <td>{$item.quantity}</td>
                    
                    <td>{if $item.service_status eq 2} <b><font color="green">{gt text='PAID'}</font></b> {else} <i><b>{gt text='DEMO'}</b></i> {/if}</td>
                   
            </tr>
            {/foreach}
        </tbody>
    </table>
    {else}
    <span>{gt text='No Records Found'}</span>
    {/if}
    {/if}
</div>
{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
<p id='news_bulkaction_control'>
    {img modname='core' set='icons/extrasmall' src='2uparrow.png' __alt='doubleuparrow'} &nbsp;<a  href="javascript:void(0);" id="news_select_all">{gt text="Check all"}</a> / <a href="javascript:void(0);" id="news_deselect_all">{gt text="Uncheck all"}</a>
     &nbsp;
  <!--  <select id='news_bulkaction_select' name='news_bulkaction_select'>
        <option value='0' selected='selected'>{gt text='With selected:'}</option>
        {*<option value='1'>{gt text='Delete'}</option>*}
        <option value='2'>{gt text='Reactivate Demo'}</option>
        {*<option value='3'>{gt text='Publish'}</option>
        <option value='4'>{gt text='Reject'}</option>
        <option value='5'>{gt text='Change categories'}</option>*}
    </select>-->

 <a id="news_bulkaction_select" href=""  title="{gt text="Reactivate"}">{img modname=core src="button_ok.png" set="icons/extrasmall" __alt="Reactivate" __title="Reactivate"} {gt text="Reactivate"}</a>
</p>
{/securityutil_checkpermission_block}
<input type='hidden' name='news_bulkaction_categorydata' id='news_bulkaction_categorydata' value='' />

</form>

{adminfooter}


<div id='news_changeCategoriesForm' style='display: none;'>
    <form class='z-form' method='post' action="#" enctype="application/x-www-form-urlencoded">
        <div>
            <fieldset>
                <legend>{gt text='Select a Category'}</legend>
                <div class="z-formrow">
                    <label>{gt text='Category'}</label>
                    {gt text='Choose category' assign='lblDef'}
                    {foreach from=$catregistry key='property' item='category'}
                    <div class="z-formnote">{selector_category category=$category name=$property field='id' defaultValue='0' editLink=false defaultText=$lblDef}</div>
                    {/foreach}
                </div>
            </fieldset>
        </div>
    </form>
</div>

<script type="text/javascript">
    Zikula.UI.Tooltips($$('.reduce'));
</script>




