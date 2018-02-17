{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/test1.js'}
{adminheader}

<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Service Basket'}</h3>
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
                  
                    <th>{gt text='Service Name'}</th>
                    <th>{gt text='Shop'}</th>
                    <th>{gt text='Quantity'}</th>
                    <th>{gt text='Price'}</th>
                    <th>{gt text='Action'}</th>
               
                  
                </tr>
            </thead>
            <tbody>
                {foreach from=$servicebasket  key=k item='basket'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <!--  <td><input type="checkbox" name="zselex_selected_shops[]" value="{$k}" class="zselex_checkbox" /></td> -->
                    <td>{$basket.plugin_name|safetext}</td>
                    <td>{$basket.shop_name|safetext}</td>
                    <td>{$basket.quantity|safetext}</td>
                    <td>{$basket.newprice|safetext}</td>
                    <td><a href='index.php?module=zselex&type=admin&func=deleteBasket1&id={$k}'>{gt text='Delete'}</a></td>
                </tr>
              
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="5">{gt text='No services currently in basket.'}</td></tr>
                {/foreach}
                <tr class="z-datatableempty"><td colspan="5" align="right">Total Amount:{if $granTotal neq ''} {$granTotal} {else} 0 {/if} per month</td></tr>
                <tr><td colspan="5" align="right"><b><a href="index.php?module=zselex&type=admin&func=submitservices">{if $granTotal neq ''} {gt text='Continue'} {/if}</a></b></td></tr>
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