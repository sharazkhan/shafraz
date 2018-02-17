{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}

{adminheader}
 {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createservicebundle" class="z-iconlink z-icon-es-add">{gt text='Create Service Bundles'}</a>
</div>
 {/securityutil_checkpermission_block}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Service Bundles List'}</h3>
</div>

<form class="z-form" id="plugin_filter" action="{modurl modname='ZSELEX' type='admin' func='viewservicebundles'}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s Bundles listed' plural='Filter %1$s, %2$s Bundles listed' count=$total_count tag1=$filteractive tag2=$total_count}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Bundles'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='viewservicebundles'}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>
    </fieldset>
</form>
<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='viewservicebundles'}" method="post">
     <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                   <!--   <th></th> -->
                    <th>{gt text='Actions'}</th>
                    <th><a class='{$sort.class.bundle_id}' href='{$sort.url.bundle_id|safetext}'>{gt text='ID'}</a></th>
                     <th>
                        <a class='{$sort.class.sort_order}' href='{$sort.url.view|safetext}'>{gt text='Sort Order'}</a>
                        
                        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __value="SORT"}
                    </th>
                    <th>{gt text='Bundle Name'}</th>
                    <th>{gt text='Bundle Items'}</th>
                    <th>{gt text='Bundles type'}</th>
                    <th>{gt text='Bundles Description'}</th>
                    <th>{gt text='Bundle Price'}</th>
                    <th>{gt text='Calculated Price'}</th>
                    
                </tr>
            </thead>
            <tbody>
                {foreach from=$bundles item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
                   <!--   <td><input type="checkbox" name="zselex_selected_plugins[]" value="{$pluginitem.plugin_id}" class="zselex_checkbox" /></td> -->
                    <td>
                        {assign var='options' value=$item.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}
                    </td>
                    <td>{$item.bundle_id|safetext}</td>
                    <td><input type="text" size="5" name="sortorder[{$item.bundle_id}]" value="{$item.sort_order}"></td>
                    <td>{$item.bundle_name|safetext}</td>
                    <td>
                         {foreach from=$item.bundleitems item='bundleitem' key='key'}
                             {$key+1} . {$bundleitem.service_name} - {$bundleitem.qty}<br>
                         {/foreach}  
                    </td>
                    <td>{$item.bundle_type|safetext}</td>
                    <td>{$item.bundle_description|safetext}</td>
                    <td>{$item.bundle_price|safetext}</td>
                    <td>{$item.calculated_price|safetext}</td>
                   
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No bundles currently in database.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
      
    </div>
</form>

{pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages=10}

{adminfooter}


<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>