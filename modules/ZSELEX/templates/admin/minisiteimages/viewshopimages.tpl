{ajaxheader modname='ZSELEX' filename='delete_bulk.js' ui=true}
{ajaxheader imageviewer="true"}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='zikula.ui'}


{*<script type="text/javascript" src="modules/ZSELEX/javascript/dndfiles/jquery.js"></script>
<script type="text/javascript" src="modules/ZSELEX/javascript/dndfiles/ajaxupload.js"></script>*}

{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dndfiles/jquery.js'}
{pageaddvar name='javascript' value='modules/ZSELEX/javascript/dndfiles/ajaxupload.js'}

<link href="modules/ZSELEX/style/dndcss/classicTheme/style.css" rel="stylesheet" type="text/css" />


{if $smarty.request.shop_id neq ''}
{shopheader}
{else}
{adminheader}  
{/if}

<input type="hidden" id="servicelimit" value="{$servicelimit}" />
<input type="hidden" id="quantity" value="{$quantity}" />
<input type="hidden" id="uploadpath" value="{$uploadpath}" />
<input type="hidden" id="shop_id" name="shop_id" value="{$smarty.request.shop_id}" />
{securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADD}
{if $serviceerror < 1 AND !$servicedisable}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=zselex&amp;type=admin&amp;func=createShopImage&shop_id={$smarty.request.shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Image'}</a>
</div>
<input type="hidden" id="servicelimit" value="{$servicelimit}" />
<input type="hidden" id="quantity" value="{$quantity}" />
<input type="hidden" id="uploadpath" value="{$uploadpath}" />
<table class="options">
    <tbody>
        <tr>
            <td>
                <div id="drophere" style="width:580px;height:200px;border: 1px solid black;">{gt text='Drag and Drop files here'}</div>
                <div id="minisite_images" style="width:500px"></div>

            </td>

        </tr>
    </tbody>
</table>
{/if}
{/securityutil_checkpermission_block}


<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Shop Image List'}</h3>
</div>


<input type='hidden'  id='formID' value='zselex_bulkaction_minisiteimages_form' />
<form class="z-form" id="zselex_bulkaction_minisiteimages_form" name="zselex_bulkaction_minisiteimages_form" action="{modurl modname='ZSELEX' type='admin' func='viewshopimages' shop_id=$smarty.request.shop_id}" method="post">
    <div style="overflow:auto;" id="check1">
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" id="url" name="url" value="createImage" />
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
                    <th>{gt text='ID'}</th>
                    <th>{gt text='Image'}</th>
                    <th>{gt text='Display'}</th>
                    <th>{gt text='Shop Name'}</th>
                    <th>{gt text='Set Default'}<br> 
                        {if !$servicedisable}
            <div class="z-buttons z-formbuttons" align="right">
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
                {assign var="images" value="zselexdata/`$ownerName`/minisiteimages/fullsize/`$item.name`"}
                <tr class="{cycle values='z-odd,z-even'}">
                    {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
                    <td><input type="checkbox" id="mycheck" name="news_selected_articles[]" value="{$item.file_id}" class="news_checkbox" /></td>
                    {/securityutil_checkpermission_block}
                    {if !$expired AND !$servicedisable}
                    <td>

                        {*{if !$nodisquota }*}
                        <a href='{modurl modname='ZSELEX' type='admin' func='modifyshopimages' id=$item.file_id shop_id=$item.shop_id}'>
                           {img modname=core set=icons/extrasmall src='xedit.png' __title='Edit' __alt='Edit' class='tooltips'}
                    </a>
                    {*{/if}*}
                    <a href='{modurl modname='ZSELEX' type='admin' func='deleteMinisiteImage' id=$item.file_id shop_id=$item.shop_id}'>
                       {img modname=core set=icons/extrasmall src='14_layer_deletelayer.png' __title='Delete' __alt='Delete' class='tooltips'}
                </a>

            </td>
            {/if}

            <td>{$item.file_id}</td>
            <td>
                {if file_exists($images)}
                <a id="{$item.file_id}" rel="imageviewer[gallery]" title="{$item.name}" href="{$baseurl}zselexdata/{$ownerName}/minisiteimages/fullsize/{$item.name}">
                    <img src="{$baseurl}zselexdata/{$ownerName}/minisiteimages/thumb/{$item.name}">
                </a>
                {else}

                {/if}

            </td>
            <td>
                <input {$disable} type="checkbox" name="display[{$item.file_id}]" {if $item.display eq 1} checked="checked" {/if} value="{$item.file_id}">
            </td>

            <td>{$item.shop_name|safetext} </td>

            <td><input {$disable}  type="radio" name="defaultimage" id="image{$item.file_id}" {if $item.defaultImg eq 1} checked="checked" {/if} value="{$item.file_id}" />  <label for="image{$item.file_id}">{gt text='Default'}</label></td>

            <td>{$item.filedescription|safetext}</td>
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
    jQuery('#minisite_images').ajaxupload({
        url:document.location.pnbaseURL+"index.php?module=ZSELEX&type=ajax&func=upload_minisite_images",
        // allowExt:['jpg','JPG'],
        remotePath: document.getElementById('uploadpath').value ,
        dropArea:'#drophere' ,
        editFilename:true,
        maxFiles:{{$servicelimit}},
        form:'#zselex_bulkaction_minisiteimages_form'
    });
</script>

{pager rowcount=$total_count limit=$itemsperpage posvar='startnum'}

{adminfooter}


<script type="text/javascript">
    // <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
    // ]]>
</script>