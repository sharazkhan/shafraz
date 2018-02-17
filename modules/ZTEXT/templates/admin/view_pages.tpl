

{shopheader}
 {securityutil_checkpermission_block component='ZSELEX::' instance='::' level=ACCESS_ADMIN}
<div class="z-admin-content-pagetitle">
    <a href="index.php?module=ZTEXT&amp;type=admin&amp;func=createPage&shop_id={$shop_id}" class="z-iconlink z-icon-es-add">{gt text='Create Page'}</a>
</div>
 {/securityutil_checkpermission_block}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='Pages list'}</h3>
</div>

<form class="z-form" id="plugin_filter" action="" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset id="zselex_multicategory_filter"{if $filter_active} class='filteractive'{/if}>
        {if $filter_active}{gt text='active' assign=filteractive}{elseif $filter_active neq ''}{gt text='inactive' assign=filteractive}{else $filter_active eq ''}{gt text='All' assign=filteractive}{/if}
        <legend>{gt text='Filter %1$s, %2$s page listed' plural='Filter %1$s, %2$s pages listed' count=$total_count tag1=$filteractive tag2=$total_count}</legend>
        <input type="hidden" name="startnum" value="{$startnum}" />
        <input type="hidden" name="order" value="{$order}" />
        <input type="hidden" name="sdir" value="{$sdir}" />
        <label for="searchtext">{gt text='Identifier'}</label>
        <input type="text" name="searchtext" value="{$searchtext}" />
        <label for="status">{gt text='Status'}</label>
        {html_options name='status' id='status' options=$itemstatus selected=$status}
        &nbsp;
        &nbsp;&nbsp;
        <span class="z-nowrap z-buttons">
            <input class='z-bt-filter' name="submit" type="submit" value="{gt text='Filter'}" />
            <a href="{modurl modname="ZTEXT" type='admin' func='viewPages' shop_id=$shop_id}" title="{gt text="Clear"}">{img modname=core src="button_cancel.png" set="icons/extrasmall" __alt="Clear" __title="Clear"} {gt text="Clear"}</a>
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
                    <th><a class='{$sort.class.text_id}' href='{$sort.url.text_id|safetext}'>{gt text='ID'}</a></th>
                    
                    <th><a class='{$sort.class.headertext}' href='{$sort.url.headertext|safetext}'>{gt text='Header Text'}</a></th>
                    <th>{gt text='Body'}</th>
                    <th>{gt text='Status'}</th>
                    
                </tr>
            </thead>
            <tbody>
                {foreach from=$pages item='item'}
                <tr class="{cycle values='z-odd,z-even'}">
                    <td>
                    <a  href="{modurl modname='ZTEXT' type='admin' func='deletePage' shop_id=$shop_id text_id=$item.text_id}">{img modname='core' set='icons/extrasmall' src='14_layer_deletelayer.png' title='Delete' alt="Delete" class='tooltips'}</a>
                    <a href="{modurl modname='ZTEXT' type='admin' func='editPage' shop_id=$shop_id text_id=$item.text_id}" >{img modname='core' set='icons/extrasmall' src='xedit.png' title='Edit' alt="Edit" class='tooltips'}</a>
                    </td>
                    <td>{$item.text_id|safetext}</td>
                    <td>{$item.headertext|safetext|wordwrap:14:"\n":true}</td>
                    <td>{shorttext text=$item.bodytext|nl2br|wordwrap:14:"\n":true len=200}</td>
                    <td>{$aStatus[$item.active]|safetext}</td>
                    
                </tr>
                {foreachelse}
                <tr class="z-datatableempty"><td colspan="10">{gt text='No pages found.'}</td></tr>
                {/foreach}
            </tbody>
        </table>
      
    </div>
</form>

{pager rowcount=$total_count limit=$itemsperpage posvar='startnum' maxpages=10}

{adminfooter}


<script type="text/javascript">
// <![CDATA[
    Zikula.UI.Tooltips($$('.tooltips'));
// ]]>
</script>