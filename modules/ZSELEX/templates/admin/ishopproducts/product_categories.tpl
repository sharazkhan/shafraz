
<script>
     //alert('helloo');
     //document.getElementById('backshield').style.display = 'block';
     window.parent.document.getElementById("manage_content").style.backgroundImage = 'none';
   
   
</script>
{*<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createProductCategory&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">Create Product Category</a>
</div>*}
{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createProdCategory&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Category'}</a>
</div>
 {/securityutil_checkpermission_block}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Product categorys list'}</h3>
</div>

<form class="z-form" id="category_filter" action="" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s category listed' plural='Filter %1$s, %2$s categorys listed' count=$total_categorys tag1=$filteractive tag2=$total_categorys}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Category Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='productCategories' shop_id=$smarty.request.shop_id}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
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
                    <th><a class='{$sort.class.prd_cat_id}' href='{$sort.url.prd_cat_id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.prd_cat_name}' href='{$sort.url.prd_cat_name|safetext}'>{gt text='category'}</a></th>
                    <th>{gt text='Status'}</th>
                    
                </tr>
            </thead>
            <tbody>
                {foreach from=$categorysitems item='categoryitem'}
                <tr class="{cycle values='z-odd,z-even'}">
                   <!--   <td><input type="checkbox" name="zselex_selected_categorys[]" value="{$categoryitem.category_id}" class="zselex_checkbox" /></td> -->
                    <td>
                        {assign var='options' value=$categoryitem.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}
                    </td>
                    <td>{$categoryitem.prd_cat_id|safetext}</td>
                    <td>{$categoryitem.prd_cat_name|safetext}</td>
                    <td>{$aStatus[$categoryitem.status]|safetext}</td>
                     
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No categorys currently in database.'}</td></tr>
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
<form class="z-form" action="{modurl modname='ZSELEX' type='admin' func='modifycategory'}" method="post">
    <div>
        <fieldset>
            <label for="directcategory_id">{gt text='Access a past category via its ID'}:</label>
            <input type="text" id="directcategory_id" name="category_id" value="" size="5" maxlength="8" />
            <span class="z-nowrap z-buttons">
                <input class="z-bt-small" name="submit" type="submit" value="{gt text='Go retrieve'}" />
                <input class="z-bt-small" name="reset" type="reset" value="{gt text='Reset'}" />
            </span>
        </fieldset>
    </div>
</form>
-->
{pager rowcount=$total_categorys limit=$itemsperpage posvar='startnum' maxpages=10}



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