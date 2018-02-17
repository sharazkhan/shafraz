{ajaxheader modname='ZSELEX' filename='delete_bulk.js' ui=true}
{ajaxheader imageviewer="true"}
{if $smarty.request.shop_id neq ''}
{shopheader}
{else}
{adminheader}  
{/if}

{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}

<script type="text/javascript" src="modules/ZSELEX/javascript/dndfiles/jquery.js"></script>
<script type="text/javascript" src="modules/ZSELEX/javascript/dndfiles/ajaxupload.js"></script>
<link href="modules/ZSELEX/style/dndcss/classicTheme/style.css" rel="stylesheet" type="text/css" />


<input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />
<input type="hidden" id="servicelimit" value="{$servicelimit}" />
<input type="hidden" id="quantity" value="{$quantity}" />
<input type="hidden" id="uploadpath" value="{$uploadpath}" />
<input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />

{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
{if $serviceerror < 1 AND !$servicedisable}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createShopGalleryImage&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Image'}</a>
</div>


<table class="options">
    <tbody>
        <tr>
            <td>
                <div id="drophere" style="width:580px;height:200px;border: 1px solid black;">{gt text='Drag and Drop files here'}</div>
                <div id="gallery_images" style="width:500px"></div>
            </td>
        </tr>
    </tbody>
</table>


{/if}
{/securityutil_checkpermission_block}

<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Shop Gallery Images'}</h3>
</div>

<input type='hidden'  id='formID' value='zselex_bulkaction_minisitegallery_form' />
<form class="z-form" id="zselex_bulkaction_minisitegallery_form" name="zselex_bulkaction_minisitegallery_form" action="{modurl modname='ZSELEX' type='admin' func='viewshopgalleryimages' shop_id=$smarty.request.shop_id}" method="post">
    <div style="overflow:auto;">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" id="url" name="url" value="creategalleryImage" />
        {*<input type="hidden" id="servicecount" value="{$servicecount}" />*}
        <input type="hidden" id="servicelimit" value="{$servicelimit}" />
        <input type="hidden" id="quantity" value="{$quantity}" />
        <input type="hidden" id="test6" value="" />

        <table id="zselex_admintable" class="z-datatable">
            <thead>
                <tr>
                    <!--  <th></th> -->
                    {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
                    <th></th>
                    {/securityutil_checkpermission_block}
                    {if !$expired AND !$servicedisable}
                    <th>{gt text='Actions'}</th>
                    {/if}
                    <th>{gt text='Image'}</th>
                    <th>{gt text='Shop Name'}</th>
                    <th>{gt text='Set Default'}
                        {if !$servicedisable}
            <br> <div class="z-buttons z-formbuttons" align="right">
                {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save" __name="action" __value="savedefaults"}
            </div>
            {/if}
            </th>
            <th>{gt text='Description'}</th>
            <th>{gt text='Keywords'}</th>
            </tr>
            </thead>
            <tbody>
                {foreach from=$items item='item'}
                {assign var="images" value="zselexdata/`$ownerName`/minisitegallery/fullsize/`$item.image_name`"}
                <tr class="{cycle values='z-odd,z-even'}">
                    {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
                    <td><input type="checkbox" id="mycheck" name="news_selected_articles[]" value="{$item.gallery_id}" class="news_checkbox" /></td>
                    {/securityutil_checkpermission_block}
                    {if !$expired AND !$servicedisable}
                    <td>

                        {*{if $nodisquota < 1}*}
                     <a href='{modurl modname='ZSELEX' type='admin' func='modifyshopgalleryimages' id=$item.gallery_id shop_id=$item.shop_id}'>
                           {img modname=core set=icons/extrasmall src='xedit.png' __title='Edit' __alt='Edit' class='tooltips'}
                    </a>
                    {*{/if}*}
                    <a href='{modurl modname='ZSELEX' type='admin' func='deleteMinisiteGalleryImage' id=$item.gallery_id shop_id=$item.shop_id}'>
                       {img modname=core set=icons/extrasmall src='14_layer_deletelayer.png' __title='Delete' __alt='Delete' class='tooltips'}
                    </a>

                   </td>
                   {/if}

            <td>
                {if file_exists($images)}
                <a id="{$item.gallery_id}" rel="imageviewer[gallery]" title="{$item.image_name}" href="{$baseurl}zselexdata/{$ownerName}/minisitegallery/fullsize/{$item.image_name}">
                    <img src="{$baseurl}zselexdata/{$ownerName}/minisitegallery/thumb/{$item.image_name}"></a>
                {else}
                {/if}
            </td>

            <td>{$item.shop_name|safetext} </td>

            <td><input {$disable} type="radio" name="defaultimage" id="image{$item.gallery_id}" {if $item.defaultImg eq 1} checked="checked" {/if} value="{$item.gallery_id}" />  <label for="image{$item.gallery_id}">{gt text='Default'}</label></td>

            <td>{$item.image_description|safetext}</td>
            <td>{$item.keywords|safetext}</td>

        </tr>
        {foreachelse}
        <tr class="z-datatableempty"><td colspan="16">{gt text='No images found.'}</td></tr>
        {/foreach}
    </tbody>
</table>

</div>


{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
<p id='news_bulkaction_control'>
    {img modname='core' set='icons/extrasmall' src='2uparrow.png' __alt='doubleuparrow'} &nbsp;<a href="javascript:void(0);" id="news_select_all">{gt text="Check all"}</a> / <a href="javascript:void(0);" id="news_deselect_all">{gt text="Uncheck all"}</a>
    &nbsp;
    <select id='news_bulkaction_select' name='news_bulkaction_select'>
        <option value='0' selected='selected'>{gt text='With selected:'}</option>
        <option value='1'>{gt text='Delete'}</option>

    </select>
</p>
{/securityutil_checkpermission_block}
<input type='hidden' name='news_bulkaction_categorydata' id='news_bulkaction_categorydata' value='' />
</form>
<script type="text/javascript">
    jQuery('#gallery_images').ajaxupload({
        url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=upload_gallery_images",
        allowExt:['jpg' , 'png' , 'jpeg' , 'gif'],
        remotePath: document.getElementById('uploadpath').value ,
        dropArea:'#drophere',
        editFilename:true,
        maxFiles:{{$servicelimit}},
        form:'#zselex_bulkaction_minisitegallery_form'
    });
</script>

{pager rowcount=$total_count limit=$itemsperpage posvar='startnum'}

{adminfooter}

<div id='news_changeCategoriesForm' style='display: none;'>
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