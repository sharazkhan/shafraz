{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}

{adminheader}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createbranch" class="z-iconlink z-icon-es-add">Create Branch</a>
</div>
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='New branchs list'}</h3>
</div>

<form class="z-form" id="country_filter" action="{modurl modname='ZSELEX' type='admin' func='viewbranch'}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s country listed' plural='Filter %1$s, %2$s branchs listed' count=$total_branchs tag1=$filteractive tag2=$total_branchs}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Branch Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='viewbranch'}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
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
                    <th><a class='{$sort.class.branch_id}' href='{$sort.url.branch_id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.branch_name}' href='{$sort.url.branch_name|safetext}'>{gt text='Branch'}</a></th>
                    <th>{gt text='Description'}</th>
                    <th>{gt text='Status'}</th>
                    <th>{gt text='Created User'}</th>
                    <th><a class='{$sort.class.cr_date}' href='{$sort.url.cr_date|safetext}'>{gt text='Created'}</a></th>
                    <th>{gt text='Updated User'}</th>
                    <th><a class='{$sort.class.lu_date}' href='{$sort.url.lu_date|safetext}'>{gt text='Updated'}</a></th>
                    <th>{gt text='Actions'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$branchsitems item='branchitem'}
                <tr class="{cycle values='z-odd,z-even'}">
                   <!--   <td><input type="checkbox" name="zselex_selected_branchs[]" value="{$branchitem.branch_id}" class="zselex_checkbox" /></td> -->
                    <td>{$branchitem.branch_id|safetext}</td>
                    <td>{$branchitem.branch_name|safetext}</td>
                    <td>{$branchitem.description|safetext}</td>
                    
                    <td>{$aStatus[$branchitem.status]|safetext}</td>
                    <td>{$branchitem.createduser|safetext}</td>
                    <td>{$branchitem.cr_date|safetext}</td>
                    <td>{$branchitem.updateduser|safetext}</td>
                    <td>{$branchitem.lu_date|safetext}</td>
                    <td>
                        {assign var='options' value=$branchitem.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}
                    </td>
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No branchs currently in database.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
        <!--  <p id='zselex_bulkaction_control'>
            {img modname='core' set='icons/extrasmall' src='2uparrow.png' __alt='doubleuparrow'}<a href="javascript:void(0);" id="zselex_select_all">{gt text="Check all"}</a> / <a href="javascript:void(0);" id="zselex_deselect_all">{gt text="Uncheck all"}</a>
            <select id='zselex_bulkaction_select' name='zselex_bulkaction_select'>
                <option value='0' selected='selected'>{gt text='With selected:'}</option>
                <option value='1'>{gt text='Delete'}</option>
                <option value='2'>{gt text='active'}</option>
            </select>
        </p> -->
    </div>
</form>
<!-- 
<form class="z-form" action="{modurl modname='ZSELEX' type='admin' func='modifycountry'}" method="post">
    <div>
        <fieldset>
            <label for="directbranch_id">{gt text='Access a past country via its ID'}:</label>
            <input type="text" id="directbranch_id" name="branch_id" value="" size="5" maxlength="8" />
            <span class="z-nowrap z-buttons">
                <input class="z-bt-small" name="submit" type="submit" value="{gt text='Go retrieve'}" />
                <input class="z-bt-small" name="reset" type="reset" value="{gt text='Reset'}" />
            </span>
        </fieldset>
    </div>
</form>
-->
{pager rowcount=$total_branchs limit=$itemsperpage posvar='startnum' maxpages=10}

{adminfooter}

<!-- This form below appears as a formdialog when a bulk action of 'change categories' is selected -->
<div id='zselex_changeCategoriesForm' style='display: none;'>
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
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>