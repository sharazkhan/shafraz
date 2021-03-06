{ajaxheader modname='ZSELEX' filename='zselex_admin.js' ui=true}
{ajaxheader imageviewer="true"}
{if $smarty.request.shop_id neq ''}
{shopheader}
{else}
{adminheader}  
{/if}
 {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createshoppdf&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Image'}</a>
</div>
 {/securityutil_checkpermission_block}
 
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Shop Pdf Images'}</h3>
</div>


<form class="z-form" id="zselex_bulkaction_form" action="{modurl modname='ZSELEX' type='admin' func='viewshoppdf' shop_id=$smarty.request.shop_id}" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        
       <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <!--  <th></th> -->
                   
                    <th>{gt text='Image'}</th>
                    <th>{gt text='Shop Name'}</th>
                  <!--  <th>{gt text='Set Default'}<br> <div class="z-buttons z-formbuttons" align="right">
        {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="typetable[action]" __value="save"}
          </div></th>-->
                    <th>{gt text='Description'}</th>
                    <th>{gt text='Actions'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$items item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>
                      <img src="{$baseurl}modules/ZSELEX/images/shoppdf/thumb/{$item.pdf_image}.jpg"></img>
                    </td>
                
                    <td>{$item.shop_name|safetext} </td>
                    <!--
                    <td><input type="radio" name="defaultimage" id="image{$item.pdf_id}" {if $item.defaultImg eq 1} checked="checked" {/if} value="{$item.pdf_id}" />  <label for="image{$item.pdf_id}">default</label></td>
                    -->
                    <td>{$item.pdf_description|safetext}</td>
                    <td><a href='{modurl modname='ZSELEX' type='admin' func='modifyshoppdf_images' id=$item.pdf_id shop_id=$item.shop_id}'>{gt text='Edit'}</a></td>
                 </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="16">{gt text='No pdf images found.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
       
    </div>
</form>

{pager rowcount=$total_count limit=$itemsperpage posvar='startnum'}

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