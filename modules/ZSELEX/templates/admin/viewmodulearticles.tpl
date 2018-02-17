{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}

{adminheader}
 {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createplugin" class="z-iconlink z-icon-es-add">{gt text='Create Plugin'}</a>
</div>
 {/securityutil_checkpermission_block}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='New plugins list'}</h3>
</div>

<form class="z-form" id="plugin_filter" action="{modurl modname='ZSELEX' type='admin' func='viewplugin'}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s plugin listed' plural='Filter %1$s, %2$s plugins listed' count=$total_plugins tag1=$filteractive tag2=$total_plugins}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Plugin Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='viewplugin'}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
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
                    <th><a class='{$sort.class.plugin_id}' href='{$sort.url.plugin_id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.plugin_name}' href='{$sort.url.plugin_name|safetext}'>{gt text='Plugin'}</a></th>
                    <th>{gt text='Type'}</th>
                    <th>{gt text='Description'}</th>
                  {*	<th>{gt text='Parent'}</th> *}
                    <th>{gt text='Price'}</th>
                    <th>{gt text='Status'}</th>
                    <th>{gt text='Created User'}</th>
                    <th><a class='{$sort.class.cr_date}' href='{$sort.url.cr_date|safetext}'>{gt text='Created'}</a></th>
                    <th>{gt text='Updated User'}</th>
                    <th><a class='{$sort.class.lu_date}' href='{$sort.url.lu_date|safetext}'>{gt text='Updated'}</a></th>
                    <th>{gt text='Actions'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$pluginsitems item='pluginitem'}
                <tr class="{cycle values='z-odd,z-even'}">
                   <!--   <td><input type="checkbox" name="zselex_selected_plugins[]" value="{$pluginitem.plugin_id}" class="zselex_checkbox" /></td> -->
                    <td>{$pluginitem.plugin_id|safetext}</td>
                    <td>{$pluginitem.plugin_name|safetext}</td>
                    <td>{$pluginitem.type|safetext}</td>
                    <td>{$pluginitem.description|safetext}</td>
                         
                    <td>{$pluginitem.price|safetext}</td>
                    <td>{$aStatus[$pluginitem.status]|safetext}</td>
                    <td>{$pluginitem.createduser|safetext}</td>
                    <td>{$pluginitem.cr_date|safetext}</td>
                    <td>{$pluginitem.updateduser|safetext}</td>
                    <td>{$pluginitem.lu_date|safetext}</td>
                    <td>
                        {assign var='options' value=$pluginitem.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}
                    </td>
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No plugins currently in database.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
       
    </div>
</form>

{pager rowcount=$total_plugins limit=$itemsperpage posvar='startnum'}

{adminfooter}
