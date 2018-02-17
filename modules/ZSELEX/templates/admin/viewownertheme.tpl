{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/test1.js'}
{adminheader}


<script type="text/javascript">
function confirmation()
{
if(confirm(Zikula.__("Do you really want to remove this theme for this shop owner?"))==true)
return true;
else
return false;
}

</script>

<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=ownertheme" class="z-iconlink z-icon-es-add">{gt text='Configure Theme for Owner'}</a>
</div>
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Shop Owners Themes'}</h3>
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
                   <th>{gt text='Theme Name'}</th>
                   <th>{gt text='Owner Name'}</th>
                   <th>{gt text='Remove'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$items  key=k item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <!--  <td><input type="checkbox" name="zselex_selected_shops[]" value="{$k}" class="zselex_checkbox" /></td> -->
                    <td>{$item.theme_name|safetext}</td>
                    <td>{$item.uname|safetext}</td>
                    <td><a onclick='return confirmation()' href="index.php?module=zselex&amp;type=admin&amp;func=removeOwnerTheme&id={$item.id|safetext}">{gt text='Remove'}</a></td>
                    
                </tr>
              
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="5">{gt text='No thems configured.'}</td></tr>
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