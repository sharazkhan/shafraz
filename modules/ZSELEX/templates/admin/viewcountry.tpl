{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}

{adminheader}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createcountry" class="z-iconlink z-icon-es-add">Create Country</a>
</div>
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='New countrys list'}</h3>
</div>

<form class="z-form" id="country_filter" action="{modurl modname='ZSELEX' type='admin' func='viewcountry'}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s country listed' plural='Filter %1$s, %2$s countrys listed' count=$total_countrys tag1=$filteractive tag2=$total_countrys}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Country Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='viewcountry'}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
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
                    <th><a class='{$sort.class.country_id}' href='{$sort.url.country_id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.countryname}' href='{$sort.url.countryname|safetext}'>{gt text='country'}</a></th>
                    <th>{gt text='Description'}</th>
                   	{* <th>{gt text='Parent'}</th> *}
                    <th>{gt text='Status'}</th>
                    
                  {* <th>{gt text='Created User'}</th> *}
                    <th><a class='{$sort.class.cr_date}' href='{$sort.url.cr_date|safetext}'>{gt text='Created'}</a></th>
                  {*  <th>{gt text='Updated User'}</th> *}
                    <th><a class='{$sort.class.lu_date}' href='{$sort.url.lu_date|safetext}'>{gt text='Updated'}</a></th>
                   
                </tr>
            </thead>
            <tbody>
                {foreach from=$countrysitems item='countryitem'}
                <tr class="{cycle values='z-odd,z-even'}">
                   <!--   <td><input type="checkbox" name="zselex_selected_countrys[]" value="{$countryitem.country_id}" class="zselex_checkbox" /></td> -->
                    
                     <td>
                        {assign var='options' value=$countryitem.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}
                    </td>
                    <td>{$countryitem.country_id|safetext}</td>
                    <td>{$countryitem.country_name|safetext}</td>
                    <td>{$countryitem.description|safetext}</td>
                     {*
                    <td width="35%">
                   <table>
                    <tbody>
                    <tr> 
                    <td ><b>{gt text='Name'}</b></td>
                    <td><b>{gt text='Type'}</b></td>
                    </tr>
                    {if $countryitem.parentcountry}
                        {foreach from=$countryitem.parentcountry item='parentitem'}
                        {if $parentitem.parentId}
                            <tr>
                                <td>{$parentitem.country_name|safetext}</td>
                                <td> {$parentitem.parentType|safetext}</td>
                            </tr>
                         {/if}   
                         {/foreach}
                     {/if}
                    {if $countryitem.parentregion}
                        {foreach from=$countryitem.parentregion item='parentitem'}
                        {if $parentitem.parentId}
                            <tr>
                                <td>{$parentitem.region_name|safetext}</td>
                                <td> {$parentitem.parentType|safetext}</td>
                            </tr>
                            {/if}
                         {/foreach}
                     {/if}
                     {if $countryitem.parentcity}
                        {foreach from=$countryitem.parentcity item='parentitem'}
                         {if $parentitem.parentId}
                            <tr>
                                <td>{$parentitem.city_name|safetext}</td>
                                <td> {$parentitem.parentType|safetext}</td>
                            </tr>
                            {/if}
                         {/foreach}
                     {/if}

                    {if $countryitem.parentshop}
                         {foreach from=$countryitem.parentshop item='parentitem'}
                         {if $parentitem.parentId}
                            <tr>
                                <td> {$parentitem.shop_name|safetext} </td>
                                <td> {$parentitem.parentType|safetext}</td>
                            </tr>
                            {/if}
                        {/foreach}
                    {/if}

                    {if $countryitem.parentad}
                         {foreach from=$countryitem.parentad item='parentitem'}
                         {if $parentitem.parentId}
                        <tr>
                            <td> {$parentitem.name|safetext} </td>
                            <td> {$parentitem.parentType|safetext}</td>
                         </tr>
                         {/if}
                         {/foreach}
                     {/if}

                    {if $countryitem.parentplugin}
                        {foreach from=$countryitem.parentplugin item='parentitem'}
                        {if $parentitem.parentId}
                            <tr>
                                <td>{$parentitem.plugin_name|safetext} </td>
                                <td> {$parentitem.parentType|safetext}</td>
                            </tr>
                           {/if} 
                         {/foreach}
                     {/if}

                    </tbody>
                    </table>
                     </td>
                     *}

                    <td>{$aStatus[$countryitem.status]|safetext}</td>
                  {*  <td>{$countryitem.createduser|safetext}</td> *}
                    <td>{$countryitem.cr_date|safetext}</td>
                  {*  <td>{$countryitem.updateduser|safetext}</td> *}
                    <td>{$countryitem.lu_date|safetext}</td>
                   
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No countrys currently in database.'}</td></tr>
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
            <label for="directcountry_id">{gt text='Access a past country via its ID'}:</label>
            <input type="text" id="directcountry_id" name="country_id" value="" size="5" maxlength="8" />
            <span class="z-nowrap z-buttons">
                <input class="z-bt-small" name="submit" type="submit" value="{gt text='Go retrieve'}" />
                <input class="z-bt-small" name="reset" type="reset" value="{gt text='Reset'}" />
            </span>
        </fieldset>
    </div>
</form>
-->
{pager rowcount=$total_countrys limit=$itemsperpage posvar='startnum' maxpages=10}

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