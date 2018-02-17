 {adminheader}
 {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createshoptype" class="z-iconlink z-icon-es-add">{gt text='Create Shop Type'}</a>
</div>
 {/securityutil_checkpermission_block}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Shop Type List'}</h3>
</div>

<form class="z-form" id="plugin_filter" action="{modurl modname='ZSELEX' type='admin' func='viewshoptypes'}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s types listed' plural='Filter %1$s, %2$s types listed' count=$total_count tag1=$filteractive tag2=$total_count}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Shop Type'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='viewshoptypes'}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
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
                    <th><a class='{$sort.class.id}' href='{$sort.url.shoptype_id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.shoptype}' href='{$sort.url.shoptype|safetext}'>{gt text='Shop Type'}</a></th>
                    <th>{gt text='Actions'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$shoptypes item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
                   <!--   <td><input type="checkbox" name="zselex_selected_plugins[]" value="{$pluginitem.plugin_id}" class="zselex_checkbox" /></td> -->
                    <td>{$item.shoptype_id|safetext}</td>
                    <td>{$item.shoptype|safetext}</td>
                   
                    <td>
                        {assign var='options' value=$item.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}
                    </td>
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No shop types currently in database.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
      
    </div>
</form>

{pager rowcount=$total_count limit=$itemsperpage posvar='startnum'}

{adminfooter}


<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>