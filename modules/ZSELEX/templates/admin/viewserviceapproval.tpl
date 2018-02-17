{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}

{adminheader}
 


<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Service Approval List'}</h3>
</div>

<form class="z-form" id="advertise_filter" action="{modurl modname='ZSELEX' type='admin' func='viewadvertise'}" method="post" enctype="application/x-www-form-urlencoded">
  {*  <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s advertise listed' plural='Filter %1$s, %2$s advertises listed' count=$total_advertises tag1=$filteractive tag2=$total_advertises}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Advertise Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='viewadvertise'}" title="{gt text='Clear'}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text='Clear'}</a>
        </span>
    </fieldset>  *}
</form>
<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='processbulkaction'}" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                  <!--    <th></th> -->
                  
                    <th>{gt text='Shop Name'}</th>
                    <th>{gt text='User Name'}</th>
                    <th>{gt text='Service Name'}</th>
                  
                    <th>{gt text='Type'}</th>
                    <th>{gt text='Quantity'}</th>
                    
                    <th>{gt text='Actions'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$aprovalitems item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
                   <!--   <td><input type="checkbox" name="zselex_selected_advertises[]" value="{$advertiseitem.advertise_id}" class="zselex_checkbox" /></td> -->
                    <td>{$item.shop_name|safetext}</td>
                    <td>{$item.uname|safetext}</td>
                     <td>{$item.plugin_name|safetext}</td>
                    <td>{$item.type|safetext}</td>
                     <td>{$item.quantity|safetext}</td>
                   
                    <td>
                        {assign var='options' value=$item.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}
                    </td>
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="6">{gt text='No services to approve in database.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
        <!--  <p id='zselex_bulkaction_control'>
            {img modname='core' set='icons/extrasmall' src='2uparrow.png' __alt='doubleuparrow'}<a href="javascript:void(0);" id="zselex_select_all">{gt text='Check all'}</a> / <a href="javascript:void(0);" id="zselex_deselect_all">{gt text='Uncheck all'}</a>
            <select id='zselex_bulkaction_select' name='zselex_bulkaction_select'>
                <option value='0' selected='selected'>{gt text='With selected:'}</option>
                <option value='1'>{gt text='Delete'}</option>
                <option value='2'>{gt text='active'}</option>
            </select>
        </p> -->
    </div>
</form>
<!-- 
<form class="z-form" action="{modurl modname='ZSELEX' type='admin' func='modifyadvertise'}" method="post">
    <div>
        <fieldset>
            <label for="directadvertise_id">{gt text='Access a past advertise via its ID'}:</label>
            <input type="text" id="directadvertise_id" name="advertise_id" value="" size="5" maxlength="8" />
            <span class="z-nowrap z-buttons">
                <input class="z-bt-small" name="submit" type="submit" value="{gt text='Go retrieve'}" />
                <input class="z-bt-small" name="reset" type="reset" value="{gt text='Reset'}" />
            </span>
        </fieldset>
    </div>
</form>
-->
{pager rowcount=$total_advertises limit=$itemsperpage posvar='startnum'}

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