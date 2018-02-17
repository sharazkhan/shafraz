{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}

{adminheader}

<div class="z-admin-content-pagetitle">
       <a href="index.php?module=zselex&amp;type=admin&amp;func=viewzselextheme">{gt text='View ZSELEX Themes'}</a>
</div>



<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text=' Configure Theme for ZSELEX'}</h3>
</div>

<form class="z-form" id="type_filter" action="{modurl modname='ZSELEX' type='admin' func='configurethemezselex'}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s theme listed' plural='Filter %1$s, %2$s themes listed' count=$total_types tag1=$filteractive tag2=$total_types}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Type Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
       
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='configurethemezselex'}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>
    </fieldset>
</form>
<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='configurethemezselex'}" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                  <!--    <th></th> -->
                    <th><a class='{$sort.class.id}' href='{$sort.url.id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.name}' href='{$sort.url.name|safetext}'>{gt text='Theme'}</a></th>
                    <th>{gt text='Description'}</th>
                    <th>
                        {gt text='Configure to zselex'}
                        <br>
                         <input type="submit" name="submit" value="configure">
                   </th>
                   <th></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$typesitems item='typeitem'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <!--  <td><input type="checkbox" name="zselex_selected_types[]" value="{$typeitem.type_id}" class="zselex_checkbox" /></td> -->
                    <td>{$typeitem.id|safetext}</td>
                    <td>{$typeitem.name|safetext}</td>
                    <td>{$typeitem.description|safetext}</td>
                    <td>
                        {*
                        <input type="radio"  {if $typeitem.id eq $typeitem.theme_id} disabled="disabled"  {/if} name="zselextheme" value="{$typeitem.name}|{$typeitem.id}">
                        *}
                    <input type="checkbox"  {if $typeitem.id eq $typeitem.theme_id} disabled="disabled"  {/if} name=zselextheme[{$typeitem.id}] value="{$typeitem.name}">
                        </td>
                    <td>
                        {if $typeitem.id eq $typeitem.theme_id}
                            <b><i>{gt text='Owners Theme'}</i></b>
                          {else}  
                        {/if}
                    </td>
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No types currently in database.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
       <!--   <p id='zselex_bulkaction_control'>
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
<form class="z-form" action="{modurl modname='ZSELEX' type='admin' func='modifytype'}" method="post">
    <div>
        <fieldset>
            <label for="directtype_id">{gt text='Access a past type via its ID'}:</label>
            <input type="text" id="directtype_id" name="type_id" value="" size="5" maxlength="8" />
            <span class="z-nowrap z-buttons">
                <input class="z-bt-small" name="submit" type="submit" value="{gt text='Go retrieve'}" />
                <input class="z-bt-small" name="reset" type="reset" value="{gt text='Reset'}" />
            </span>
        </fieldset>
    </div>
</form>
-->
{pager rowcount=$total_types limit=$itemsperpage posvar='startnum' maxpages=10}

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