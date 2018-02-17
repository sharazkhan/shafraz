{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}
{insert name='getstatusmsg'}
{shopheader}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dndfiles/jquery.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dndfiles/ajaxupload.js'}
<link href="modules/ZSELEX/style/dndcss/classicTheme/style.css" rel="stylesheet" type="text/css" />
<style>
    .ax-legend{display:none;} 
    .ax-button{display:none;} 
    .aj-frame{border:none !important; background: none !important}
</style>

<input type="hidden" id="shop_id" value="{$smarty.request.shop_id}" />
<input type="hidden" id="servicelimit" value="{$servicelimit}" />
<input type="hidden" id="quantity" value="{$quantity}" />
<input type="hidden" id="uploadpath" value="{$uploadpath}" />


{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
{if $serviceerror < 1 AND !$servicedisable}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createevent&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Event'}</a>
</div>
{/if}      
{/securityutil_checkpermission_block}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Shop Event List'}</h3>
</div>

{if $serviceerror < 1 AND !$servicedisable}
<form class="z-form" id="type_filter" action="{modurl modname='ZSELEX' type='admin' func='viewshopevent' shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
              {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
              <legend>{gt text='Filter %1$s, %2$s theme listed' plural='Filter %1$s, %2$s themes listed' count=$total_types tag1=$filteractive tag2=$total_types}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Event Name'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />

        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZSELEX" type='admin' func='viewshopevent' shop_id=$smarty.request.shop_id}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>
    </fieldset>
</form>
{/if}
<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='viewzselextheme'}" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                  <!--    <th></th> -->
                    {if !$expired AND !$servicedisable}
                    <th>{gt text='Actions'}</th>
                    {/if}
                    <th><a class='{$sort.class.shop_event_id}' href='{$sort.url.shop_event_id|safetext}'>{gt text='ID'}</a></th>
                    <th><a class='{$sort.class.shop_event_name}' href='{$sort.url.shop_event_name|safetext}'>{gt text='Event Name'}</a></th>
                    <th>{gt text='Shop Description'}</th>
                    <th>{gt text='Description'}</th>
                    <th>{gt text='Start Date'}</th>
                    <th>{gt text='Start Time'}</th>
                    <th>{gt text='End Date'}</th>
                    <th>{gt text='End Time'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$typesitems item='typeitem'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <!--  <td><input type="checkbox" name="zselex_selected_types[]" value="{$typeitem.type_id}" class="zselex_checkbox" /></td> -->

                    {if !$expired AND !$servicedisable}
                    <td>

                        {assign var='options' value=$typeitem.options}
                        {section name='options' loop=$options}
                        <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}

                    </td>
                    {/if}

                    <td>{$typeitem.shop_event_id|safetext}</td>
                    <td>{$typeitem.shop_event_name|safetext}</td>
                    <td>{$typeitem.shop_event_shortdescription|safetext}</td>
                    <td>{$typeitem.shop_event_description|safetext}</td>
                    <td>{$typeitem.shop_event_startdate}</td>
                    <td>
                        {$typeitem.shop_event_starthour}:{$typeitem.shop_event_startminute}

                    </td>
                    <td>{$typeitem.shop_event_enddate}</td>
                    <td>
                        {$typeitem.shop_event_endhour}:{$typeitem.shop_event_endminute}
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