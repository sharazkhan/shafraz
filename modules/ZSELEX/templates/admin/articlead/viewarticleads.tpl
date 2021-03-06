{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}

{shopheader}



<script type="text/javascript">
    function confirmation()
    {
        if(confirm(Zikula.__("Do you really want to delete this Ad?"))==true)
            return true;
        else
            return false;
    }

</script>

{if $servicecount > 0 AND !$servicedisable}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createarticlead&shop_id={$shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Advertise'}</a>
</div>
{/if}

<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='New advertises list'}</h3>
</div>

<form class="z-form" id="advertise_filter" action="{modurl modname='ZSELEX' type='admin' func='viewarticleads' shop_id=$smarty.request.shop_id}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
              {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
              <legend>{gt text='Filter %1$s, %2$s advertise listed' plural='Filter %1$s, %2$s advertises listed' count=$total_articleads tag1=$filteractive tag2=$total_advertises}</legend>
        {if $servicecount > 0 AND !$servicedisable}
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
            <a href="{modurl modname="ZSELEX" type='admin' func='viewarticleads' shop_id=$smarty.request.shop_id}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
        </span>
        {/if}
    </fieldset>
</form>
<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='processbulkaction'}" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                  <!--    <th></th> -->
                    {if !$expired AND !$servicedisable}
                    <th>{gt text='Actions'}</th>
                    {/if}

                    <th>{gt text='Name'}</th>
                    <th>{gt text='Shop Name'}</th>
                    <th>{gt text='Country Name'}</th>
                    <th>{gt text='Region Name'}</th>
                    <th>{gt text='City Name'}</th>
                    <th>{gt text='Level'}</th>
                    <th>{gt text='Keywords'}</th>
                    <th>{gt text='Status'}</th>

                    <th>{gt text='Start Date'}</th>
                    <th>{gt text='End Date'}</th>

                </tr>
            </thead>
            <tbody>
                {foreach from=$advertisesitems item='advertiseitem'}
                <tr class="{cycle values='z-odd,z-even'}">
                   <!--   <td><input type="checkbox" name="zselex_selected_advertises[]" value="{$advertiseitem.advertise_id}" class="zselex_checkbox" /></td> -->
                    {if !$expired AND !$servicedisable}
                    <td>

                        {assign var='options' value=$advertiseitem.options}
                        {section name='options' loop=$options}
                        {$options[options].val}
                        <a href="{$options[options].url|safetext}"  {if $options[options].title eq 'Delete'} id="confirmdialog" {*onclick='return confirmation()'*} {/if}>{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title class='tooltips'}</a>
                        {/section}


                    </td>
                    {/if}

                    <td>{$advertiseitem.name|safetext}</td>
                    <td>{$advertiseitem.shop_name|safetext}</td>
                    <td>{$advertiseitem.country_name|safetext}</td>
                    <td>{$advertiseitem.region_name|safetext}</td>
                    <td>{$advertiseitem.city_name|safetext}</td>
                    <td>{$advertiseitem.level|safetext}</td>


                    <td>{$advertiseitem.keywords|safetext}</td>
                    <td>{$aStatus[$advertiseitem.status]|safetext}</td>
                    <td>{$advertiseitem.start_date|safetext}</td>
                    <td>{$advertiseitem.end_date|safetext}</td>

                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="20">{gt text='No advertises currently in database.'}</td></tr>
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
{pager rowcount=$total_articleads limit=$itemsperpage posvar='startnum' maxpages=10}

<script type="text/javascript">
   
     
</script>

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