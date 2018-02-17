{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/test1.js'}

{if $smarty.request.shop_id neq ''}
{shopheader}
{else}
{adminheader}  
{/if}
{if $serviceerror < 1 AND !$servicedisable}
<div class="z-admin-content-pagetitle">
    {if $perm eq 1}
    <a href="index.php?module=zselex&amp;type=admin&amp;func=dotd&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create DEAL OF THE DAY for this shop'}</a>
    {/if}

</div>
{/if}

<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='DEAL OF THE DAY'}</h3>
</div>

<form class="z-form" id="shop_filter" action="{modurl modname='ZSELEX' type='admin' func='viewshop'}" method="post" enctype="application/x-www-form-urlencoded">

</form>

<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='processbulkaction'}" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <!--  <th></th> -->
                    {if !$expired AND !$servicedisable}
                    <th>{gt text='Actions'}</th>
                    {/if}
                    <th>{gt text='DOTD Name'}</th>
                    <th>{gt text='Shop'}</th>
                    <th>{gt text='dotd_date'}</th>
                    <th>{gt text='Column'}</th>
                    <th>{gt text='Value'}</th>

                </tr>
            </thead>
            <tbody>
                {foreach from=$dotd item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <!--  <td><input type="checkbox" name="zselex_selected_shops[]" value="{$shopitem.shop_id}" class="zselex_checkbox" /></td> -->

                    {if !$expired AND !$servicedisable}
                    <td>
                        <a href='index.php?module=zselex&type=admin&func=modifydotd&dotdId={$item.dotdId}&shop_id={$item.shop_id}'>
                            {img modname=core set=icons/extrasmall src='xedit.png' __title='Edit' __alt='Edit' class='tooltips'}
                        </a>
                        <a href='index.php?module=zselex&type=admin&func=deleteDotd&dotdId={$item.dotdId}&shop_id={$item.shop_id}'>
                            {img modname=core set=icons/extrasmall src='14_layer_deletelayer.png' __title='Delete' __alt='Delete' class='tooltips'}
                        </a>
                    </td>
                    {/if}


                    <td>{$item.dotd_name|safetext}</td>
                    <td>{$item.shop_name|safetext}</td>
                    <td>{$item.dotd_date|safetext}</td>
                    <td>{$item.column_name|safetext}</td>
                    <td>{$item.value|safetext}</td>
                </tr>

                {foreachelse}
                <tr class="z-datatableempty"><td colspan="7">{gt text='No DOTD.'}</td></tr>
                {/foreach}

            </tbody>
        </table>

    </div>
</form>


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

<noscript>


{php}

//echo "helllooo terstinggg";


{/php}


</noscript>